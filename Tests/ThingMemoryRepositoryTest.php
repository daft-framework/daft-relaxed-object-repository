<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository;

use InvalidArgumentException;

/**
 * @template S as array{id:int, name:string}
 * @template S2 as array{id:int|string, name:string}
 * @template T1 as Fixtures\Thing
 * @template T2 as array<string, scalar|array|object|null>
 * @template T3 as Fixtures\ThingMemoryRepository
 * @template T4 as Fixtures\ThingMemoryRepository
 *
 * @template-extends ObjectRepositoryTest<S, S2, T1, T2, T3, T4>
 */
final class ThingMemoryRepositoryTest extends ObjectRepositoryTest
{
	/**
	 * @return list<
	 *	array{
	 *		0:class-string<T3>,
	 *		1:T2,
	 *		2:list<S>,
	 *		3:list<S2>
	 *	}
	 * >
	 */
	public function dataProviderAppendObject() : array
	{
		/**
		 * @var list<
		 *	array{
		 *		0:class-string<T3>,
		 *		1:T2,
		 *		2:list<S>,
		 *		3:list<S2>
		 *	}
		 * >
		 */
		return [
			[
				Fixtures\ThingMemoryRepository::class,
				[
				],
				[
					[
						'id' => 0,
						'name' => 'foo',
					],
				],
				[
					[
						'id' => '1',
						'name' => 'foo',
					],
				],
			],
		];
	}

	/**
	 * @return list<
	 *	array{
	 *		0:class-string<T4>,
	 *		1:T2,
	 *		2:array<string, scalar|null>,
	 *		3:array<string, scalar|null>,
	 *		4:array<string, scalar|null>
	 *	}
	 * >
	 */
	public function dataProviderPatchObject() : array
	{
		/**
		 * @var list<
		 *	array{
		 *		0:class-string<T4>,
		 *		1:T2,
		 *		2:array<string, scalar|null>,
		 *		3:array<string, scalar|null>,
		 *		4:array<string, scalar|null>
		 *	}
		 * >
		 */
		return [
			[
				Fixtures\ThingMemoryRepository::class,
				[
				],
				[
					'id' => 0,
					'name' => 'foo',
				],
				[
					'name' => 'bar',
				],
				[
					'id' => 1,
					'name' => 'bar',
				],
			],
		];
	}

	public function test_thing_fails() : void
	{
		$object = new Fixtures\Thing(1, 'nope');

		static::assertSame(1, $object->id);
		static::assertSame('nope', $object->name);

		static::expectException(InvalidArgumentException::class);
		static::expectExceptionMessage('Argument 1 must be a digit!');

		new Fixtures\Thing('nope', 'nope');
	}
}
