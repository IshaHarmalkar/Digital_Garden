<?php

namespace Database\Seeders;

use App\Models\Mood;
use Illuminate\Database\Seeder;

class MoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // HAPPY section
            ['primary' => 'Happy', 'secondary' => 'Optimistic', 'tertiary' => 'Hopeful'],
            ['primary' => 'Happy', 'secondary' => 'Optimistic', 'tertiary' => 'Inspired'],
            ['primary' => 'Happy', 'secondary' => 'Intimate', 'tertiary' => 'Loving'],
            ['primary' => 'Happy', 'secondary' => 'Intimate', 'tertiary' => 'Affectionate'],
            ['primary' => 'Happy', 'secondary' => 'Peaceful', 'tertiary' => 'Loving'],
            ['primary' => 'Happy', 'secondary' => 'Peaceful', 'tertiary' => 'Grateful'],
            ['primary' => 'Happy', 'secondary' => 'Powerful', 'tertiary' => 'Creative'],
            ['primary' => 'Happy', 'secondary' => 'Powerful', 'tertiary' => 'Courageous'],
            ['primary' => 'Happy', 'secondary' => 'Accepted', 'tertiary' => 'Fulfilled'],
            ['primary' => 'Happy', 'secondary' => 'Accepted', 'tertiary' => 'Respected'],
            ['primary' => 'Happy', 'secondary' => 'Proud', 'tertiary' => 'Confident'],
            ['primary' => 'Happy', 'secondary' => 'Proud', 'tertiary' => 'Successful'],
            ['primary' => 'Happy', 'secondary' => 'Interested', 'tertiary' => 'Curious'],
            ['primary' => 'Happy', 'secondary' => 'Interested', 'tertiary' => 'Inquisitive'],
            ['primary' => 'Happy', 'secondary' => 'Content', 'tertiary' => 'Free'],
            ['primary' => 'Happy', 'secondary' => 'Content', 'tertiary' => 'Joyful'],
            ['primary' => 'Happy', 'secondary' => 'Playful', 'tertiary' => 'Aroused'],
            ['primary' => 'Happy', 'secondary' => 'Playful', 'tertiary' => 'Cheeky'],

            // SURPRISE section
            ['primary' => 'Surprise', 'secondary' => 'Excited', 'tertiary' => 'Eager'],
            ['primary' => 'Surprise', 'secondary' => 'Excited', 'tertiary' => 'Energetic'],
            ['primary' => 'Surprise', 'secondary' => 'Amazed', 'tertiary' => 'Awe'],
            ['primary' => 'Surprise', 'secondary' => 'Amazed', 'tertiary' => 'Astonished'],
            ['primary' => 'Surprise', 'secondary' => 'Confused', 'tertiary' => 'Disillusioned'],
            ['primary' => 'Surprise', 'secondary' => 'Confused', 'tertiary' => 'Perplexed'],
            ['primary' => 'Surprise', 'secondary' => 'Startled', 'tertiary' => 'Shocked'],
            ['primary' => 'Surprise', 'secondary' => 'Startled', 'tertiary' => 'Dismayed'],

            // ANGER section
            ['primary' => 'Anger', 'secondary' => 'Mad', 'tertiary' => 'Hurt'],
            ['primary' => 'Anger', 'secondary' => 'Mad', 'tertiary' => 'Hostile'],
            ['primary' => 'Anger', 'secondary' => 'Aggressive', 'tertiary' => 'Resentful'],
            ['primary' => 'Anger', 'secondary' => 'Aggressive', 'tertiary' => 'Provocative'],
            ['primary' => 'Anger', 'secondary' => 'Frustrated', 'tertiary' => 'Infuriated'],
            ['primary' => 'Anger', 'secondary' => 'Frustrated', 'tertiary' => 'Annoyed'],
            ['primary' => 'Anger', 'secondary' => 'Distant', 'tertiary' => 'Withdrawn'],
            ['primary' => 'Anger', 'secondary' => 'Distant', 'tertiary' => 'Numb'],
            ['primary' => 'Anger', 'secondary' => 'Critical', 'tertiary' => 'Sarcastic'],
            ['primary' => 'Anger', 'secondary' => 'Critical', 'tertiary' => 'Skeptical'],

            // FEAR section
            ['primary' => 'Fear', 'secondary' => 'Scared', 'tertiary' => 'Frightened'],
            ['primary' => 'Fear', 'secondary' => 'Scared', 'tertiary' => 'Helpless'],
            ['primary' => 'Fear', 'secondary' => 'Anxious', 'tertiary' => 'Overwhelmed'],
            ['primary' => 'Fear', 'secondary' => 'Anxious', 'tertiary' => 'Worried'],
            ['primary' => 'Fear', 'secondary' => 'Insecure', 'tertiary' => 'Inadequate'],
            ['primary' => 'Fear', 'secondary' => 'Insecure', 'tertiary' => 'Inferior'],
            ['primary' => 'Fear', 'secondary' => 'Weak', 'tertiary' => 'Worthless'],
            ['primary' => 'Fear', 'secondary' => 'Weak', 'tertiary' => 'Insignificant'],
            ['primary' => 'Fear', 'secondary' => 'Rejected', 'tertiary' => 'Excluded'],
            ['primary' => 'Fear', 'secondary' => 'Rejected', 'tertiary' => 'Persecuted'],
            ['primary' => 'Fear', 'secondary' => 'Threatened', 'tertiary' => 'Nervous'],
            ['primary' => 'Fear', 'secondary' => 'Threatened', 'tertiary' => 'Exposed'],

            // SAD section
            ['primary' => 'Sad', 'secondary' => 'Guilty', 'tertiary' => 'Ashamed'],
            ['primary' => 'Sad', 'secondary' => 'Guilty', 'tertiary' => 'Remorseful'],
            ['primary' => 'Sad', 'secondary' => 'Despair', 'tertiary' => 'Powerless'],
            ['primary' => 'Sad', 'secondary' => 'Despair', 'tertiary' => 'Vulnerable'],
            ['primary' => 'Sad', 'secondary' => 'Depressed', 'tertiary' => 'Empty'],
            ['primary' => 'Sad', 'secondary' => 'Depressed', 'tertiary' => 'Inferior'],
            ['primary' => 'Sad', 'secondary' => 'Lonely', 'tertiary' => 'Abandoned'],
            ['primary' => 'Sad', 'secondary' => 'Lonely', 'tertiary' => 'Isolated'],
            ['primary' => 'Sad', 'secondary' => 'Bored', 'tertiary' => 'Apathetic'],
            ['primary' => 'Sad', 'secondary' => 'Bored', 'tertiary' => 'Indifferent'],
            ['primary' => 'Sad', 'secondary' => 'Tired', 'tertiary' => 'Sleepy'],
            ['primary' => 'Sad', 'secondary' => 'Tired', 'tertiary' => 'Unfocused'],

            // DISGUST section
            ['primary' => 'Disgust', 'secondary' => 'Disapproving', 'tertiary' => 'Judgmental'],
            ['primary' => 'Disgust', 'secondary' => 'Disapproving', 'tertiary' => 'Embarrassed'],
            ['primary' => 'Disgust', 'secondary' => 'Disappointed', 'tertiary' => 'Appalled'],
            ['primary' => 'Disgust', 'secondary' => 'Disappointed', 'tertiary' => 'Revolted'],
            ['primary' => 'Disgust', 'secondary' => 'Awful', 'tertiary' => 'Nauseated'],
            ['primary' => 'Disgust', 'secondary' => 'Awful', 'tertiary' => 'Detestable'],
            ['primary' => 'Disgust', 'secondary' => 'Repugnant', 'tertiary' => 'Horrified'],
            ['primary' => 'Disgust', 'secondary' => 'Repugnant', 'tertiary' => 'Hesitant'],
        ];

        foreach ($data as $row) {
            Mood::create($row);
        }
    }
}
