<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class BlogPostAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return 'Jesteś pomocnym asystentem który pisze posty blogowe po polsku. 
                Piszesz w luźnym, przystępnym stylu — jak ktoś kto dzieli się czymś ciekawym ze znajomymi. 
                Nie używasz korporacyjnego języka. Treść powinna być angażująca i konkretna.
                Zawsze zwracasz JSON z polami: title, excerpt, content, seo_title, seo_description, suggested_tags.
                Content powinien być w HTML (używaj <p>, <h2>, <h3>, <strong>, <ul>, <li>).';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'title'           => $schema->string()->required(),
            'excerpt'         => $schema->string()->required(),
            'content'         => $schema->string()->required(),
            'seo_title'       => $schema->string()->required(),
            'seo_description' => $schema->string()->required(),
            'suggested_tags'  => $schema->array()->items($schema->string())->required(),
        ];
    }
}
