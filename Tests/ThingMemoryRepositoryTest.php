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
 * @template S3 as array{name:string}
 * @template T1 as Fixtures\Thing
 * @template T2 as array<string, scalar|array|object|null>
 * @template T3 as Fixtures\ThingMemoryRepository
 * @template T4 as Fixtures\ThingMemoryRepository
 *
 * @template-extends ObjectRepositoryTest<S, S2, S3, T1, T2, T3, T4>
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
	 *		2:S,
	 *		3:S3,
	 *		4:S2
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
		 *		2:S,
		 *		3:S3,
		 *		4:S2
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

	/**
	 * @dataProvider dataProviderAppendObject
	 *
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::__construct()
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::ForgetObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::MaybeRecallObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::RecallObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::RelaxedObjectHash()
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::UpdateObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\MemoryRepository::AppendObjectFromArray()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\MemoryRepository::MaybeRecallObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\MemoryRepository::ObtainIdFromObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\MemoryRepository::RemoveObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\MemoryRepository::UpdateObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\Thing::__construct()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\ThingMemoryRepository::__construct()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\ThingMemoryRepository::AppendObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\ThingMemoryRepository::ConvertObjectToSimpleArray()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\ThingMemoryRepository::ConvertSimpleArrayToObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::MaybeRecallManyObjects()
	 *
	 * @param class-string<T3> $repo_type
	 * @param T2 $repo_args
	 * @param list<S> $append_these
	 * @param list<S2> $expect_these
	 */
	public function test_append_object(
		string $repo_type,
		array $repo_args,
		array $append_these,
		array $expect_these
	) : void {
		parent::test_append_object(
			$repo_type,
			$repo_args,
			$append_these,
			$expect_these
		);
	}

	/**
	 * @dataProvider dataProviderAppendObject
	 *
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::__construct()
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::MaybeRecallObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::RecallObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::RelaxedObjectHash()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\MemoryRepository::MaybeRecallObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\MemoryRepository::ObtainIdFromObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\Thing::__construct()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\ThingMemoryRepository::__construct()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\ThingMemoryRepository::ConvertSimpleArrayToObject()
	 *
	 * @depends test_append_object
	 *
	 * @param class-string<T3> $repo_type
	 * @param T2 $repo_args
	 * @param list<S> $append_these
	 * @param list<S2> $expect_these
	 */
	public function test_default_failure(
		string $repo_type,
		array $repo_args,
		array $append_these,
		array $expect_these
	) : void {
		parent::test_default_failure(
			$repo_type,
			$repo_args,
			$append_these,
			$expect_these
		);
	}

	/**
	 * @dataProvider dataProviderAppendObject
	 *
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::__construct()
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::MaybeRecallObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::RecallObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::RelaxedObjectHash()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\MemoryRepository::MaybeRecallObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\MemoryRepository::ObtainIdFromObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\Thing::__construct()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\ThingMemoryRepository::__construct()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\ThingMemoryRepository::ConvertSimpleArrayToObject()
	 *
	 * @depends test_append_object
	 *
	 * @param class-string<T3> $repo_type
	 * @param T2 $repo_args
	 * @param list<S> $append_these
	 * @param list<S2> $expect_these
	 */
	public function test_custom_failure(
		string $repo_type,
		array $repo_args,
		array $append_these,
		array $expect_these
	) : void {
		parent::test_custom_failure(
			$repo_type,
			$repo_args,
			$append_these,
			$expect_these
		);
	}

	/**
	 * @dataProvider dataProviderPatchObject
	 *
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::__construct()
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::MaybeRecallObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::RecallObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::RelaxedObjectHash()
	 * @covers \DaftFramework\RelaxedObjectRepository\AbstractObjectRepository::UpdateObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\MemoryRepository::AppendObjectFromArray()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\MemoryRepository::MaybeRecallObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\MemoryRepository::ObtainIdFromObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\MemoryRepository::PatchObjectData()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\MemoryRepository::UpdateObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\Thing::__construct()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\ThingMemoryRepository::__construct()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\ThingMemoryRepository::AppendObject()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\ThingMemoryRepository::ConvertObjectToSimpleArray()
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\ThingMemoryRepository::ConvertSimpleArrayToObject()
	 *
	 * @depends test_append_object
	 *
	 * @param class-string<T4> $repo_type
	 * @param T2 $repo_args
	 * @param S $append_this
	 * @param S3 $patch_this
	 * @param S2 $expect_this
	 */
	public function test_patch_object(
		string $repo_type,
		array $repo_args,
		array $append_this,
		array $patch_this,
		array $expect_this
	) : void {
		parent::test_patch_object(
			$repo_type,
			$repo_args,
			$append_this,
			$patch_this,
			$expect_this
		);
	}

	/**
	 * @covers \DaftFramework\RelaxedObjectRepository\Fixtures\Thing::__construct()
	 */
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
