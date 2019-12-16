<?php

namespace spec\EzSystems\EzPlatformQueryFieldType\eZ\Persistence\Legacy\Content\FieldValue\Converter;

use eZ\Publish\Core\Persistence\Legacy\Content\StorageFieldDefinition;
use eZ\Publish\SPI\Persistence\Content\FieldTypeConstraints;
use eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition;
use EzSystems\EzPlatformQueryFieldType\eZ\Persistence\Legacy\Content\FieldValue\Converter\QueryConverter;
use PhpSpec\ObjectBehavior;
use Webmozart\Assert\Assert;

class QueryConverterSpec extends ObjectBehavior
{
    const PARAMETERS = ['param1' => 'value1', 'param2' => 'value2'];
    const QUERY_TYPE = 'SomeQueryType';
    const RETURNED_TYPE = 'folder';

    function getFieldDefinition(): FieldDefinition
    {
        $fieldDefinition = new FieldDefinition();
        $fieldDefinition->fieldTypeConstraints->fieldSettings = [
            'QueryType' => self::QUERY_TYPE,
            'ReturnedType' => self::RETURNED_TYPE,
            'Parameters' => self::PARAMETERS
        ];

        return $fieldDefinition;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(QueryConverter::class);
    }

    function it_stores_the_Parameters_in_dataText5_in_json_format()
    {
        $storageFieldDefinition = new StorageFieldDefinition();
        $this->toStorageFieldDefinition($this->getFieldDefinition(), $storageFieldDefinition);
        Assert::eq($storageFieldDefinition->dataText5, \json_encode(self::PARAMETERS));
    }

    function it_stores_the_QueryType_in_dataText1()
    {
        $storageFieldDefinition = new StorageFieldDefinition();
        $this->toStorageFieldDefinition($this->getFieldDefinition(), $storageFieldDefinition);
        Assert::eq($storageFieldDefinition->dataText1, self::QUERY_TYPE);
    }

    function it_stores_the_ReturnedType_in_dataText2()
    {
        $storageFieldDefinition = new StorageFieldDefinition();
        $this->toStorageFieldDefinition($this->getFieldDefinition(), $storageFieldDefinition);
        Assert::eq($storageFieldDefinition->dataText2, self::RETURNED_TYPE);
    }
}
