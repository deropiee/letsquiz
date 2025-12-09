<?php

namespace Tests\Unit;

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Tests\TestCase;

class QuizListUnitTest extends TestCase
{
    /**
     * Basis: zonder chapter parameter wordt eerste chapter geselecteerd.
     */
    public function test_it_groups_folders_into_chapters_and_selects_first_by_default()
    {
        // Arrange: fake storage en maak directories aan
        Storage::fake('public');
        foreach (['h1-alpha','h1-beta','h2-gamma'] as $dir) {
            Storage::disk('public')->makeDirectory('quizzes/'.$dir);
        }

        $controller = new QuizController();

        // Act
        $view = $controller->list();

        // Assert
        $this->assertInstanceOf(View::class, $view);
        $data = $view->getData();

        $this->assertArrayHasKey('chapters', $data);
        $this->assertCount(2, $data['chapters']); // h1 & h2

        // Controleer structuur van eerste chapter
        $first = $data['chapters'][0];
        $this->assertEquals('h1', $first['key']);
        $this->assertEquals(1, $first['index']);
        $this->assertCount(2, $first['items']);
        $this->assertEquals('alpha', strtolower($first['items'][0]['title']));

        // Geselecteerde chapter default
        $this->assertEquals('h1', $data['selectedChapterKey']);
        $this->assertEquals('h1', $data['selectedChapter']['key']);
    }

    /**
     * Selectie via index (2) moet second chapter kiezen.
     */
    public function test_it_selects_chapter_by_index_parameter()
    {
        Storage::fake('public');
        foreach (['h1-alpha','h1-beta','h2-gamma'] as $dir) {
            Storage::disk('public')->makeDirectory('quizzes/'.$dir);
        }

        $controller = new QuizController();

        $view = $controller->list(2); // index 2 -> h2
        $data = $view->getData();

        $this->assertEquals('h2', $data['selectedChapterKey']);
        $this->assertEquals('h2', $data['selectedChapter']['key']);
    }

    /**
     * Selectie via key naam (h2).
     */
    public function test_it_selects_chapter_by_key_parameter()
    {
        Storage::fake('public');
        foreach (['h1-alpha','h1-beta','h2-gamma'] as $dir) {
            Storage::disk('public')->makeDirectory('quizzes/'.$dir);
        }

        $controller = new QuizController();

        $view = $controller->list('h2');
        $data = $view->getData();

        $this->assertEquals('h2', $data['selectedChapterKey']);
        $this->assertEquals('h2', $data['selectedChapter']['key']);
    }
}
