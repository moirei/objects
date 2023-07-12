<?php

use MOIREI\Objects\Tests\Objects\TestObject_1;

it('should create object instance', function () {
    $object = new TestObject_1();

    expect($object)->toBeInstanceOf(TestObject_1::class);
});

it('should set object property', function () {
    $object = new TestObject_1();
    $object->name = 'test';

    expect($object->name)->toEqual('test');
    expect($object->get('name'))->toEqual('test');
    expect($object['name'])->toEqual('test');
});

it('should set object property via setter', function () {
    $object = new TestObject_1();
    $object->set('name', 'test');

    expect($object->name)->toEqual('test');
    expect($object->get('name'))->toEqual('test');
    expect($object['name'])->toEqual('test');
});

it('should set object property via offset', function () {
    $object = new TestObject_1();
    $object['name'] = 'test';

    expect($object->name)->toEqual('test');
    expect($object->get('name'))->toEqual('test');
    expect($object['name'])->toEqual('test');
});

it('should set object property via constructor', function () {
    $object = new TestObject_1([
        'name' => 'test'
    ]);

    expect($object->name)->toEqual('test');
    expect($object->get('name'))->toEqual('test');
    expect($object['name'])->toEqual('test');
});

it('should set object property via fill method', function () {
    $object = new TestObject_1();
    $object->fill([
        'name' => 'test'
    ]);

    expect($object->name)->toEqual('test');
    expect($object->get('name'))->toEqual('test');
    expect($object['name'])->toEqual('test');
});

it('should fail to set undefined property', function () {
    $object = new TestObject_1();
    $object->email = 'test';
})->throws(\Exception::class);

it('should fail to get undefined property', function () {
    $object = new TestObject_1();
    $email = $object->email;
})->throws(\Exception::class);

it('should check own property', function () {
    $object = new TestObject_1();

    expect($object->hasOwnProperty('name'))->toBeTrue();
    expect($object->hasOwnProperty('email'))->toBeFalse();
});

it('should convert object to array', function () {
    $object = new TestObject_1([
        'name' => 'test',
    ]);

    $array = $object->toArray();

    expect($array)->toBeArray();
    expect(isset($array['name']))->toBeTrue();
    expect(count($array))->toEqual(1);
    expect($array['name'])->toEqual('test');
});
