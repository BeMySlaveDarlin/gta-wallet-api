<?php

declare(strict_types=1);

use Cycle\Annotated\Embeddings;
use Cycle\Annotated\Entities;
use Cycle\Annotated\MergeColumns;
use Cycle\Annotated\MergeIndexes;
use Cycle\Annotated\TableInheritance;
use Cycle\Schema\Generator\GenerateModifiers;
use Cycle\Schema\Generator\GenerateRelations;
use Cycle\Schema\Generator\GenerateTypecast;
use Cycle\Schema\Generator\RenderModifiers;
use Cycle\Schema\Generator\RenderRelations;
use Cycle\Schema\Generator\RenderTables;
use Cycle\Schema\Generator\ResetTables;
use Cycle\Schema\Generator\ValidateEntities;
use Spiral\Attributes\AttributeReader;
use Spiral\Tokenizer\Config\TokenizerConfig;
use Spiral\Tokenizer\Tokenizer;

$tokenizer = new Tokenizer(
    new TokenizerConfig([
        'directories' => [
            SRC_PATH . 'Database' . DS . 'Entity',
        ],
    ])
);

return [
    new ResetTables(),
    new Embeddings($tokenizer->classLocator(), new AttributeReader()),
    new Entities($tokenizer->classLocator(), new AttributeReader()),
    new TableInheritance(),
    new MergeColumns(),
    new GenerateRelations(),
    new GenerateModifiers(),
    new ValidateEntities(),
    new RenderTables(),
    new RenderRelations(),
    new RenderModifiers(),
    new MergeIndexes(),
    new GenerateTypecast(),
];
