<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\DaftRelaxedObjectRepository;

use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase as Base;
use function random_bytes;
use RuntimeException;

/**
 * @template S as array<string, scalar|null>
 * @template S2 as array<string, scalar|null>
 * @template T as array<string, scalar|array|object|null>
 * @template T1 as object
 */
class ObjectRepositoryTest extends Base
{
	/**
	 * @return list<
	 *	array{
	 *		0:class-string<AppendableObjectRepository&ConvertingRepository>,
	 *		1:array{type:class-string<T1>},
	 *		2:list<array<string, scalar|null>>,
	 *		3:list<array<string, scalar|null>>
	 *	}
	 * >
	 */
	public function dataProviderAppendObject() : array
	{
		/**
		 * @var list<
		 *	array{
		 *		0:class-string<AppendableObjectRepository&ConvertingRepository>,
		 *		1:array{type:class-string<T1>},
		 *		2:list<array<string, scalar|null>>,
		 *		3:list<array<string, scalar|null>>
		 *	}
		 * >
		 */
		return [
			[
				Fixtures\MemoryRepository::class,
				[
					'type' => Fixtures\Thing::class,
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
	 * @template K as key-of<S>
	 *
	 * @dataProvider dataProviderAppendObject
	 *
	 * @param class-string<AppendableObjectRepository&ConvertingRepository> $repo_type
	 * @param array{type:class-string<T1>} $repo_args
	 * @param list<S> $append_these
	 * @param list<S2> $expect_these
	 */
	public function test_append_typed_object(
		string $repo_type,
		array $repo_args,
		array $append_these,
		array $expect_these
	) : void {
		$repo = new $repo_type(
			$repo_args
		);

		static::assertGreaterThan(0, count($append_these));
		static::assertCount(count($append_these), $expect_these);

		/**
		 * @var array<int, T1>
		 */
		$testing = [];

		foreach ($append_these as $i => $data) {
			$object = $repo->ConvertSimpleArrayToObject($data);

			$testing[$i] = $repo->AppendObject($object);
		}

		foreach ($testing as $object) {
			$id = $repo->ObtainIdFromObject($object);

			static::assertSame(
				$object,
				$repo->RecallObject($id)
			);

			$repo->ForgetObject($id);

			$fresh1 = $repo->MaybeRecallObject($id);

			static::assertNotNull($fresh1);

			$id = $repo->ObtainIdFromObject($object);

			$fresh2 = $repo->RecallObject($id);

			static::assertNotSame($object, $fresh1);
			static::assertNotSame($object, $fresh2);
			static::assertSame($fresh1, $fresh2);

			$repo->RemoveObject($id);

			static::assertNull(
				$repo->MaybeRecallObject($id)
			);
		}
	}

	/**
	 * @template K as key-of<S>
	 *
	 * @dataProvider dataProviderAppendObject
	 *
	 * @depends test_append_typed_object
	 *
	 * @param class-string<AppendableObjectRepository&ConvertingRepository> $repo_type
	 * @param array{type:class-string<T1>} $repo_args
	 * @param list<S> $_append_these
	 * @param list<S2> $expect_these
	 */
	public function test_default_failure(
		string $repo_type,
		array $repo_args,
		array $_append_these,
		array $expect_these
	) : void {
		$repo = new $repo_type(
			$repo_args
		);

		$data = current($expect_these);

		$object = $repo->ConvertSimpleArrayToObject($data);

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage(
			'Object could not be found for the specified id!'
		);

		$id = $repo->ObtainIdFromObject($object);

		$repo->RecallObject($id);
	}

	/**
	 * @template K as key-of<S>
	 *
	 * @dataProvider dataProviderAppendObject
	 *
	 * @depends test_append_typed_object
	 *
	 * @param class-string<AppendableObjectRepository&ConvertingRepository> $repo_type
	 * @param array{type:class-string<T1>} $repo_args
	 * @param list<S> $_append_these
	 * @param list<S2> $expect_these
	 */
	public function test_custom_failure(
		string $repo_type,
		array $repo_args,
		array $_append_these,
		array $expect_these
	) : void {
		$repo = new $repo_type(
			$repo_args
		);

		$data = current($expect_these);

		$object = $repo->ConvertSimpleArrayToObject($data);
		$id = $repo->ObtainIdFromObject($object);
		$random = bin2hex(random_bytes(16));

		$this->expectException(Exception::class);
		$this->expectExceptionMessage($random);

		$repo->RecallObject($id, new Exception($random));
	}

	/**
	 * @return list<
	 *	array{
	 *		0:class-string<AppendableObjectRepository&PatchableObjectRepository&ConvertingRepository>,
	 *		1:array{type:class-string<T1>},
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
		 *		0:class-string<AppendableObjectRepository&PatchableObjectRepository&ConvertingRepository>,
		 *		1:array{type:class-string<T1>},
		 *		2:array<string, scalar|null>,
		 *		3:array<string, scalar|null>,
		 *		4:array<string, scalar|null>
		 *	}
		 * >
		 */
		return [
			[
				Fixtures\MemoryRepository::class,
				[
					'type' => Fixtures\Thing::class,
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
	 * @template K as key-of<S>
	 *
	 * @dataProvider dataProviderPatchObject
	 *
	 * @depends test_append_typed_object
	 *
	 * @param class-string<AppendableObjectRepository&PatchableObjectRepository&ConvertingRepository> $repo_type
	 * @param array{type:class-string<T1>} $repo_args
	 * @param array<string, scalar|null> $append_this
	 * @param array<string, scalar|null> $patch_this
	 * @param array<string, scalar|null> $expect_this
	 */
	public function test_patch_object(
		string $repo_type,
		array $repo_args,
		array $append_this,
		array $patch_this,
		array $expect_this
	) : void {
		$repo = new $repo_type(
			$repo_args
		);

		$object = $repo->ConvertSimpleArrayToObject($append_this);

		$fresh = $repo->AppendObject($object);

		$id = $repo->ObtainIdFromObject($fresh);

		$repo->PatchObjectData($id, $patch_this);

		static::assertSame(
			$expect_this,
			$repo->ConvertObjectToSimpleArray($repo->RecallObject($id))
		);
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
