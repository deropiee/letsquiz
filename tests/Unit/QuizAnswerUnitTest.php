<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Category;
use App\Models\Question;
use App\Models\Choice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizAnswerUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_answers_question_correctly()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Handmatig een category, question en choices aanmaken
        $category = Category::create([
            'name' => 'Test Category',
            'subcategory' => 'Test Subcategory',
            'slug' => 'test-category'
        ]);

        $question = Question::create([
            'category_id' => $category->id,
            'question_text' => 'Wat is 2 + 2?',
            'question_id' => 'Q1'
        ]);

        $correct = Choice::create([
            'question_id' => $question->id,
            'choice_text' => '4',
            'choice_id' => 'A',
            'is_correct' => true
        ]);

        $wrong = Choice::create([
            'question_id' => $question->id,
            'choice_text' => '3',
            'choice_id' => 'B',
            'is_correct' => false
        ]);

        // Test of de vraag correct wordt beantwoord
        $this->assertTrue($correct->is_correct);
        $this->assertFalse($wrong->is_correct);
        $this->assertEquals('Wat is 2 + 2?', $question->question_text);
    }

    public function test_user_answers_question_incorrectly()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Handmatig een category, question en choices aanmaken
        $category = Category::create([
            'name' => 'Test Category',
            'subcategory' => 'Test Subcategory',
            'slug' => 'test-category-2'
        ]);

        $question = Question::create([
            'category_id' => $category->id,
            'question_text' => 'Wat is 5 + 5?',
            'question_id' => 'Q2'
        ]);

        $correct = Choice::create([
            'question_id' => $question->id,
            'choice_text' => '10',
            'choice_id' => 'A',
            'is_correct' => true
        ]);

        $wrong = Choice::create([
            'question_id' => $question->id,
            'choice_text' => '8',
            'choice_id' => 'B',
            'is_correct' => false
        ]);

        // Test of de verkeerde keuze inderdaad fout is
        $this->assertTrue($correct->is_correct);
        $this->assertFalse($wrong->is_correct);
        $this->assertEquals('Wat is 5 + 5?', $question->question_text);

        // Test dat er 2 keuzes zijn voor deze vraag
        $this->assertEquals(2, $question->choices()->count());
    }
}
