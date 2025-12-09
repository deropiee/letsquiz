<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Question;
use App\Models\Choice;
use Illuminate\Support\Str;

class ImportQuizXml extends Command
{
    // Toegevoegd: --all optie om alle quizmappen in één keer te importeren
    protected $signature = 'quiz:import-xml {folder? : Naam van de quizmap} {--all : Importeer alle quizmappen}';
    protected $description = 'Importeer quizvragen uit een specifieke quizmap, kies er interactief één, of importeer alles met --all';

    public function handle()
    {
        $this->info("Zoek in: " . storage_path('app/public/quizzes'));

        if (!Storage::disk('public')->exists('quizzes')) {
            $this->error("Map 'quizzes' bestaat niet in storage/app/public/");
            return;
        }

        $quizFolders = Storage::disk('public')->directories('quizzes');

        if (empty($quizFolders)) {
            $this->error("Geen quizmappen gevonden in storage/app/public/quizzes/");
            return;
        }

        // Mapnaam ophalen
        $selectedFolder = $this->argument('folder');

        $importAll = (bool)$this->option('all');

        // Validatie: niet tegelijk folder én --all
        if ($selectedFolder && $importAll) {
            $this->error("Gebruik óf een folder naam óf --all, niet allebei.");
            return Command::INVALID;
        }

        if ($importAll) {
            $this->info('🔁 Importeer ALLE quizmappen...');
            $bar = $this->output->createProgressBar(count($quizFolders));
            $bar->start();
            foreach ($quizFolders as $folderPath) {
                $this->newLine();
                $this->importFolder($folderPath);
                $bar->advance();
            }
            $bar->finish();
            $this->newLine(2);
            $this->info('🎉 Alle quizmappen geïmporteerd.');
            return Command::SUCCESS;
        }

        if (!$selectedFolder) {
            // Laat gebruiker kiezen
            $selectedFolder = $this->choice(
                'Kies een quizmap om te importeren',
                array_map('basename', $quizFolders)
            );
        }

        // Volledig pad naar de gekozen map
        $folderPath = collect($quizFolders)->first(function ($path) use ($selectedFolder) {
            return basename($path) === $selectedFolder;
        });

        if (!$folderPath) {
            $this->error("Map '{$selectedFolder}' niet gevonden.");
            return Command::FAILURE;
        }

        $this->importFolder($folderPath);

        $this->info("🎉 Import voltooid!");
    }

    protected function importFolder($folder)
    {
        $folderName = basename($folder);
        $this->info("Verwerk map: {$folderName}");

        // Splits mapnaam in hoofdcategorie en subcategorie
        $parts = explode('_', $folderName);
        $guid = array_pop($parts);
        $main = $parts[0] ?? 'Onbekend';
        $sub = implode('_', array_slice($parts, 1));

        $category = Category::firstOrCreate(
            ['folder_guid' => $guid],
            ['name' => trim($main), 'subcategory' => trim($sub)]
        );

        // Haal alle bestanden in deze map op
        $files = Storage::disk('public')->files($folder);
        $this->info("Bestanden in map: " . json_encode($files));

        $xmlIdentifiers = [];

        foreach ($files as $file) {
            if (!str_contains($file, 'choiceInteraction')) {
                $this->warn("Overgeslagen: {$file}");
                continue;
            }

            $xmlPath = storage_path("app/public/{$file}");
            $this->info("Verwerk bestand: {$xmlPath}");

            $xml = simplexml_load_file($xmlPath);
            if (!$xml) {
                $this->error("Kon XML niet laden: {$file}");
                continue;
            }

            $identifier = (string) $xml['identifier'];
            $xmlIdentifiers[] = $identifier;

            // Haal de <p> node op en converteer naar tekst waarbij we ALLE geneste tekst behouden (zoals binnen <strong>)
            // Het eerdere (string) casten verloor de tekst binnen nested tags en gaf bijvoorbeeld: "Wat is ?"
            $pNode = $xml->itemBody->div->p ?? null;
            if ($pNode instanceof \SimpleXMLElement) {
                // asXML() geeft de volledige <p>...</p>; strip_tags verwijdert alleen de tags maar behoudt de inhoud (incl. DigiD)
                $questionText = strip_tags($pNode->asXML());
                // Normaliseer whitespace
                $questionText = trim(preg_replace('/\s+/u', ' ', $questionText));
            } else {
                $questionText = '';
            }
            $correctId = (string) $xml->responseDeclaration->correctResponse->value;

            // Vraag updaten of aanmaken
            $question = Question::updateOrCreate(
                ['identifier' => $identifier, 'category_id' => $category->id],
                ['question_text' => $questionText]
            );

            // Oude keuzes verwijderen en opnieuw toevoegen
            $question->choices()->delete();

            foreach ($xml->itemBody->choiceInteraction->simpleChoice as $choice) {
                $id = (string) $choice['identifier'];
                $text = trim((string) $choice);

                Choice::create([
                    'question_id' => $question->id,
                    'identifier' => $id,
                    'choice_text' => $text,
                    'is_correct' => $id === $correctId,
                    'mapped_value' => $id === $correctId ? 1 : 0,
                ]);
            }

            $this->info("Vraag gesynchroniseerd: {$identifier}");
        }

        // Verwijder vragen die niet meer in XML voorkomen
        Question::where('category_id', $category->id)
            ->whereNotIn('identifier', $xmlIdentifiers)
            ->delete();

        $this->info("Categorie '{$category->name}' volledig gesynchroniseerd.");
    }
}
