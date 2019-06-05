<?php

namespace Pouch\Tests\Unit;

use Pouch\Pouch;
use Pouch\Tests\TestCase;
use Pouch\Container\Item;
use Psr\Container\ContainerInterface;
use Pouch\Exceptions\NotFoundException;

class PouchTest extends TestCase
{
    public function test_storing_data_in_container()
    {
        pouch()->bind('foo', function () {
            return 'FooString';
        });

        $this->assertTrue(pouch()->has('foo'));
    }

    public function test_resolving_data_from_container()
    {
        pouch()->bind('foo', function () {
            return 'FooString';
        });

        $this->assertEquals('FooString', pouch()->resolve('foo'));
    }

    public function test_resolving_data_with_callback()
    {
        pouch()->bind('foo', function () {
            return 'FooString';
        });

        $this->assertEquals('FooString', pouch()->resolve('foo'));
    }

    public function test_resolving_inexistent_key()
    {
        $this->expectException(NotFoundException::class);

        pouch()->resolve('bar');
    }

    public function test_resolving_with_non_string_key()
    {
        pouch()->bind(5, function () {
            return 'FooString';
        });

        pouch()->resolve(5);

        $this->assertTrue(pouch()->has('5'));
        $this->assertEquals('FooString', pouch()->resolve(5));
    }

    public function test_resolving_with_non_string_key_converted()
    {
        pouch()->bind(5, function () {
            return 'FooString';
        });

        $this->assertTrue(pouch()->has('5'));
        $this->assertEquals('FooString', pouch()->resolve('5'));
    }

    public function test_pouch_callback_argument()
    {
        $expected = 'FooBar';

        pouch()->bind('foo', function () {
            return 'Foo';
        });

        pouch()->bind('foobar', function (ContainerInterface $pouch) {
            return $pouch->get('foo').'Bar';
        });

        $this->assertEquals($expected, pouch()->get('foobar'));
    }

    public function test_using_magic_prop_and_method_to_get_container_value()
    {
        pouch()->bind('foo', function () {
           return 'Foo';
        });

        $this->assertEquals('Foo', pouch()->foo);
        $this->assertEquals('Foo', pouch()->foo());
        $this->assertTrue(isset(pouch()->foo));
    }

    public function test_removing_key_from_container()
    {
        pouch()->bind('foo', function () {
            return 'Foo';
        });

        $this->assertTrue(pouch()->has('foo'));

        pouch()->remove('foo');

        $this->assertFalse(pouch()->has('foo'));
    }

    public function test_binding_multiple_values()
    {
        pouch()->bind([
            'foo' => function () {
                return 'Foo';
            },
            'bar' => function ($pouch) {
                return $pouch->resolve('foo').'Bar';
            },
            'baz' => function ($pouch) {
                return $pouch->resolve('bar').'Baz';
            }
        ]);

        $this->assertTrue(pouch()->has('foo'));
        $this->assertTrue(pouch()->has('bar'));
        $this->assertTrue(pouch()->has('baz'));
        $this->assertEquals('Foo', pouch()->resolve('foo'));
        $this->assertEquals('FooBar', pouch()->resolve('bar'));
        $this->assertEquals('FooBarBaz', pouch()->resolve('baz'));
    }

    public function test_dynamic_data_binding()
    {
        pouch()->foo(function () {
            return 'Foo';
        });

        $this->assertTrue(pouch()->has('foo'));
        $this->assertEquals('Foo', pouch()->resolve('foo'));
    }

    public function test_working_with_a_new_pouch_instance()
    {
        pouch()->bind('foo', function () {
            return 'Foo';
        });

        $pouch = new Pouch;
        $pouch->bind('bar', function () {
            return 'Bar';
        });

        $this->assertFalse(pouch()->has('bar'));
        $this->assertFalse($pouch->has('foo'));
    }

    public function test_container_factory_with_simple_type()
    {
        $min = 50;
        $max = 500000000;

        pouch()->factory()->bind('rand', function () use ($min, $max) {
            return mt_rand($min, $max);
        });

        $rand1 = pouch()->resolve('rand');
        $rand2 = pouch()->resolve('rand');

        $this->assertEquals('integer', gettype($rand1));
        $this->assertGreaterThanOrEqual($min, $rand1);
        $this->assertLessThanOrEqual($max, $rand1);

        $this->assertEquals('integer', gettype($rand2));
        $this->assertGreaterThanOrEqual($min, $rand2);
        $this->assertLessThanOrEqual($max, $rand2);

        $this->assertNotEquals($rand1, $rand2);
    }

    public function test_container_factory_with_objects()
    {
        // Factory
        pouch()->factory()->bind('fooObject', function () {
            return new \SplObjectStorage;
        });

        // Non-factory
        pouch()->bind('barObject', function () {
            return new \SplObjectStorage;
        });

        $fooObject1 = pouch()->resolve('fooObject');
        $fooObject2 = pouch()->resolve('fooObject');
        $barObject1 = pouch()->resolve('barObject');
        $barObject2 = pouch()->resolve('barObject');

        // Instances should be different on strict check
        $this->assertFalse($fooObject1 === $fooObject2);
        $this->assertTrue($fooObject1 == $fooObject2);

        // Instances should be the same on strict check\
        $this->assertTrue($barObject1 === $barObject2);
        $this->assertTrue($barObject1 == $barObject2);
    }

    public function test_accessing_container_param_from_factory_callback()
    {
        $expected = 'FooBar';

        pouch()->bind('foo', function () {
            return 'Foo';
        });

        pouch()->factory()->bind('bar', function () {
            return 'Bar';
        });

        pouch()->factory()->bind('foobar', function ($pouch) {
            return $pouch->resolve('foo').$pouch->resolve('bar');
        });

        $this->assertEquals($expected, pouch()->resolve('foobar'));
    }

    public function test_binding_multiple_values_using_factories()
    {
        pouch()->bind([
            'fooObject' => pouch()->factory(function () {
                return new \SplObjectStorage;
            })
        ]);

        $fooObject1 = pouch()->resolve('fooObject');
        $fooObject2 = pouch()->resolve('fooObject');

        // Instances should be different on strict check
        $this->assertFalse($fooObject1 === $fooObject2);
        $this->assertTrue($fooObject1 == $fooObject2);
    }

    public function test_lazy_loading_functionality()
    {
        pouch()->bind('foo', function () {
            return 'Foo';
        });

        $this->assertTrue(is_callable(pouch()->raw('foo')));
        $this->assertEquals('Foo', pouch()->resolve('foo'));
    }

    public function test_factory_with_custom_args()
    {
        $expected = ['foo', 'bar', 'baz'];

        pouch()->factory()->bind('foo', function ($pouch, $args) {
            return $args;
        });

        $this->assertEquals($expected, pouch()->withArgs(...$expected)->get('foo'));
    }

    public function test_fetching_item_instance()
    {
        pouch()->bind('foo', function () {
            return 'Foo';
        });

        $this->assertInstanceOf(Item::class, pouch()->item('foo'));
    }

    public function test_creating_key_alias()
    {
        pouch()->bind('foo', function () {
            return 'Foo';
        });

        pouch()->alias('foo_alias', 'foo');

        $fooOrig = pouch()->get('foo');
        $fooAlias = pouch()->get('foo_alias');
        $fooOrigItem = pouch()->item('foo');
        $fooAliasItem = pouch()->item('foo_alias');

        $this->assertEquals($fooOrig, $fooAlias);
        $this->assertTrue($fooOrigItem === $fooAliasItem);
        $this->assertEquals('foo', $fooAliasItem->getName());

        $fooOrigItem->setName('bar');
        $this->assertEquals('bar', $fooAliasItem->getName());

        $fooAliasItem->setName('baz');
        $this->assertEquals('baz', $fooOrigItem->getName());
    }

    public function test_mass_bind_named_and_factory_items()
    {
        pouch()->bind([
            'foo' => pouch()->named(function () {
                return 'foo';
            }),
            'bar' => pouch()->named(pouch()->factory(function () {
                return 'bar';
            })),
            'foobar' => pouch()->factory(pouch()->named(function () {
                return 'foobar';
            })),
        ]);

        $this->assertEquals('foo', pouch()->get('foo'));
        $this->assertEquals('bar', pouch()->get('bar'));
        $this->assertEquals('foobar', pouch()->get('foobar'));

        $this->assertTrue(pouch()->item('foo')->isResolvedByName());
        $this->assertTrue(pouch()->item('bar')->isResolvedByName());
        $this->assertTrue(pouch()->item('bar')->isFactory());
        $this->assertTrue(pouch()->item('foobar')->isResolvedByName());
        $this->assertTrue(pouch()->item('foobar')->isFactory());
    }

    public function test_if_count_is_correct()
    {
        $data = [
            'foo' => function () {
                return 'foo';
            },
            'bar' => function () {
                return 'bar';
            },
            'foobar' => function () {
                return 'foobar';
            },
        ];

        pouch()->bind($data);

        $this->assertEquals(count($data), count(pouch()));
    }

    public function test_before_after_each_get_hooks()
    {
        $beforeOutput = 'before:';
        $afterOutput = 'after:';
        pouch()->getHookManager()->addBeforeEachGet(function (Pouch $pouch, string $key) use (&$beforeOutput) {
            $beforeOutput .= $key;
        });

        pouch()->getHookManager()->addBeforeEachGet(function (Pouch $pouch, string $key) use (&$afterOutput) {
            $afterOutput .= $key;
        });

        pouch()->bind('foo', 'cheese');
        pouch()->bind('bar', 'cheese');
        pouch()->bind('baz', 'cheese');

        pouch()->get('foo');
        pouch()->get('bar');
        pouch()->get('baz');

        $this->assertEquals('before:foobarbaz', $beforeOutput);
        $this->assertEquals('after:foobarbaz', $afterOutput);
    }

    public function test_before_after_each_set_hooks()
    {
        $beforeOutput = 'before:';
        $afterOutput = 'after:';
        pouch()->getHookManager()->addBeforeEachSet(function (Pouch $pouch, string $key) use (&$beforeOutput) {
            $beforeOutput .= $key;
        });

        pouch()->getHookManager()->addBeforeEachSet(function (Pouch $pouch, string $key) use (&$afterOutput) {
            $afterOutput .= $key;
        });

        pouch()->bind('foo', 'cheese');
        pouch()->bind('bar', 'cheese');
        pouch()->bind('baz', 'cheese');

        $this->assertEquals('before:foobarbaz', $beforeOutput);
        $this->assertEquals('after:foobarbaz', $afterOutput);
    }

    public function test_before_after_specific_hook()
    {
        $beforeSetOutput = ' before set:';
        $afterGetOutput = ' after get:';
        $beforeGetOutput = ' before get:';

        pouch()->getHookManager()->addBeforeGet(['foo'], function (Pouch $pouch, string $key) use (&$beforeGetOutput) {
            $beforeGetOutput .= $key;
        });

        pouch()->getHookManager()->addAfterGet(['bar'], function (Pouch $pouch, string $key) use (&$afterGetOutput) {
            $afterGetOutput .= $key;
        });

        pouch()->getHookManager()->addBeforeSet(['bar', 'baz'], function (Pouch $pouch, string $key) use (&$beforeSetOutput) {
            $beforeSetOutput .= $key;
        });

        pouch()->bind('foo', 'cheese');
        pouch()->bind('bar', 'cheese');
        pouch()->bind('baz', 'cheese');

        pouch()->get('foo');
        pouch()->get('bar');
        
        $this->assertEquals(
            ' before set:foobarbaz after get:foobar before get:foobar',
            $beforeSetOutput . $afterGetOutput . $beforeGetOutput
        );
    }

    public function test_binding_primitive_types()
    {
        pouch()->bind('foo', 'Foo');

        $this->assertEquals('Foo', pouch()->get('foo'));
    }

    public function test_binding_array_without_closure()
    {
        pouch()->bind('foo', ['Foo']);

        $this->assertEquals(['Foo'], pouch()->get('foo'));
    }

    public function test_binding_array_with_dot_notation()
    {
        pouch()->bind('foo', ['Foo' => ['Bar' => 'Cheddar']]);
        
        $this->assertEquals('Cheddar', pouch()->get('foo.Foo.Bar'));
    }
}
