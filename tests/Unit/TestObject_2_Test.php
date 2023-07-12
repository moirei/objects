<?php

use MOIREI\Objects\Tests\Objects\TestObject_2;

it('should set undefined property', function () {
    $object = new TestObject_2();
    $object->email = 'test';

    expect($object->email)->toEqual('test');
    expect($object->get('email'))->toEqual('test');
    expect($object['email'])->toEqual('test');
});

it('should set properties via fill', function () {
    $object = new TestObject_2();
    $object->fill([
        'name' => 'test1',
        'email' => 'test2',
    ]);

    expect($object->name)->toEqual('test1');
    expect($object->get('name'))->toEqual('test1');
    expect($object['name'])->toEqual('test1');

    expect($object->email)->toEqual('test2');
    expect($object->get('email'))->toEqual('test2');
    expect($object['email'])->toEqual('test2');
});

it('should convert object to array', function () {
    $object = new TestObject_2([
        'name' => 'test1',
        'email' => 'test2',
    ]);

    $array = $object->toArray();

    expect($array)->toBeArray();
    expect(count($array))->toEqual(2);
    expect($array['name'])->toEqual('test1');
    expect($array['email'])->toEqual('test2');
});

it('should check own property', function () {
    $object = new TestObject_2();

    $object->email = 'test';

    expect($object->hasOwnProperty('name'))->toBeTrue();
    expect($object->hasOwnProperty('email'))->toBeFalse();
});

it('should check property', function () {
    $object = new TestObject_2();

    $object->email = 'test';

    expect($object->hasProperty('name'))->toBeTrue();
    expect($object->hasProperty('email'))->toBeTrue();
});
