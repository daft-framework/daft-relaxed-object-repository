<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository;

use Exception;
use PHPUnit\Framework\TestCase as Base;
use function random_bytes;
use RuntimeException;

/**
 * @template S as array<string, scalar|null>
 * @template S2 as array<string, scalar|null>
 * @template T1 as object
 * @template T2 as array{type:class-string}
 * @template T3 as AppendableObjectRepository&ConvertingRepository
 * @template T4 as AppendableObjectRepository&PatchableObjectRepository&ConvertingRepository
 */
abstract class ObjectRepositoryTest extends Base
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
	abstract public function dataProviderAppendObject() : array;

	/**
	 * @template K as key-of<S>
	 *
	 * @dataProvider dataProviderAppendObject
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
		/** @var AppendableObjectRepository&ConvertingRepository */
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
	 * @depends test_append_object
	 *
	 * @param class-string<T3> $repo_type
	 * @param T2 $repo_args
	 * @param list<S> $_append_these
	 * @param list<S2> $expect_these
	 */
	public function test_default_failure(
		string $repo_type,
		array $repo_args,
		array $_append_these,
		array $expect_these
	) : void {
		/** @var AppendableObjectRepository&ConvertingRepository */
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
	 * @depends test_append_object
	 *
	 * @param class-string<T3> $repo_type
	 * @param T2 $repo_args
	 * @param list<S> $_append_these
	 * @param list<S2> $expect_these
	 */
	public function test_custom_failure(
		string $repo_type,
		array $repo_args,
		array $_append_these,
		array $expect_these
	) : void {
		/** @var AppendableObjectRepository&ConvertingRepository */
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
	 *		0:class-string<T4>,
	 *		1:T2,
	 *		2:array<string, scalar|null>,
	 *		3:array<string, scalar|null>,
	 *		4:array<string, scalar|null>
	 *	}
	 * >
	 */
	abstract public function dataProviderPatchObject() : array;

	/**
	 * @template K as key-of<S>
	 *
	 * @dataProvider dataProviderPatchObject
	 *
	 * @depends test_append_object
	 *
	 * @param class-string<T4> $repo_type
	 * @param T2 $repo_args
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
		/** @var AppendableObjectRepository&PatchableObjectRepository&ConvertingRepository */
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
}
