Introductie
Ons laravel 12 project laat je quizzen spelen van het consortiumberoepsonderwijs. De quizzen zijn in dit project meer opgevrolijkt zodat de jongeren meer plezier hebben met het maken van de quizzen

In deze webapp kun je quizzen maken, speel zoveel mogelijk quizzen en verdien superveel gems. Gebruik de gems in de shop of draai het dagelijkse rad om meer de verdienen.

hoe moet je installeren:
clone de repository

in terminal:
cd ./Letsquiz
composer install
php artisan migrate -> schrijf daarna 2 keer "yes"
cp .env.example .env
php artisan key:generate
npm install
npm run build
php artisan serve
php artisan quiz:import-xml
import nu alle xml bestanden (functie om alles in een keer op te slaan komt misschien later)
Opstarten
nu in 1 terminal zet je:

php -S localhost:8000 -t public/ dit is om de server te draaien
en in de andere terminal doe je:

npm run dev dit is zodat alle css en js bestanden ingeladen worden.
ga nu naar http://localhost:8000 en bekijk de site

