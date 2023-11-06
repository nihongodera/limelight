<?php

declare(strict_types=1);

namespace Limelight\Parse\PartOfSpeech;

use Limelight\Classes\LimelightWord;

interface PartOfSpeech
{
    /**
     * Handle the parsing request.
     */
    public function handle(
        array $properties,
        ?LimelightWord $previousWord,
        ?array $previousToken,
        array $currentToken,
        ?array $nextToken
    ): array;
}
