<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\DaftTypedObject;

use Exception;
use PHPUnit\Framework\TestCase as Base;
use function random_bytes;
use RuntimeException;

/**
* @template S as array<string, scalar|null>
* @template S2 as array<string, scalar|null>
* @template T as array<string, scalar|array|object|null>
* @template T1 as DaftTypedObjectForRepository
*/
class DaftTypedObjectRepositoryTest extends Base
{
	/**
	* @return list<
		array{
			0:class-string<AppendableTypedObjectRepository>,
			1:array{type:class-string<T1>},
			2:list<array<string, scalar|null>>,
			3:list<array<string, scalar|null>>
		}
	>
	*/
	public function dataProviderAppendTypedObject() : array
	{
		/**
		* @var list<
			array{
				0:class-string<AppendableTypedObjectRepository>,
				1:array{type:class-string<T1>},
				2:list<array<string, scalar|null>>,
				3:list<array<string, scalar|null>>
			}
		>
		*/
		return [
			[
				Fixtures\DaftTypedObjectMemoryRepository::class,
				[
					'type' => Fixtures\MutableForRepository::class,
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
	* @dataProvider dataProviderAppendTypedObject
	*
	* @param class-string<AppendableTypedObjectRepository> $repo_type
	* @param array{type:class-string<T1>} $repo_args
	* @param list<S> $append_these
	* @param list<S2> $expect_these
	*/
	public function testAppendTypedObject(
		string $repo_type,
		array $repo_args,
		array $append_these,
		array $expect_these
	) : void {
		$repo = new $repo_type(
			$repo_args
		);

		$object_type = $repo_args['type'];

		$this->assertGreaterThan(0, count($append_these));
		$this->assertCount(count($append_these), $expect_these);

		/**
		* @var array<int, T1>
		*/
		$testing = [];

		foreach ($append_these as $i => $data) {
			$object = $object_type::__fromArray($data);

			$testing[$i] = $repo->AppendTypedObject($object);
		}

		foreach ($testing as $i => $object) {
			$this->assertSame(
				$expect_these[$i],
				$object->__toArray()
			);

			$this->assertSame(
				$object,
				$repo->RecallTypedObject($object->ObtainId())
			);

			$repo->ForgetTypedObject($object->ObtainId());

			$fresh1 = $repo->MaybeRecallTypedObject($object->ObtainId());

			$this->assertNotNull($fresh1);

			$fresh2 = $repo->RecallTypedObject($object->ObtainId());

			$this->assertNotSame($object, $fresh1);
			$this->assertNotSame($object, $fresh2);
			$this->assertSame($fresh1, $fresh2);

			$this->assertSame($expect_these[$i], $object->jsonSerialize());
			$this->assertSame($expect_these[$i], $fresh1->jsonSerialize());
			$this->assertSame($expect_these[$i], $fresh2->jsonSerialize());

			$repo->RemoveTypedObject($object->ObtainId());

			$this->assertNull(
				$repo->MaybeRecallTypedObject($object->ObtainId())
			);
		}
	}

	/**
	* @template K as key-of<S>
	*
	* @dataProvider dataProviderAppendTypedObject
	*
	* @depends testAppendTypedObject
	*
	* @param class-string<AppendableTypedObjectRepository> $repo_type
	* @param array{type:class-string<T1>} $repo_args
	* @param list<S> $_append_these
	* @param list<S2> $expect_these
	*/
	public function testDefaultFailure(
		string $repo_type,
		array $repo_args,
		array $_append_these,
		array $expect_these
	) : void {
		$repo = new $repo_type(
			$repo_args
		);

		$object_type = $repo_args['type'];

		$data = current($expect_these);

		/**
		* @var array<int, K>
		*/
		$data_keys = array_keys($data);

		/**
		* @var T
		*/
		$object_args = array_combine($data_keys, array_map(
			/**
			* @param K $property
			* @param S[K] $value
			*
			* @return T[K]
			*/
			function ($property, $value) use ($object_type) {
				/**
				* @var T[K]
				*/
				return $object_type::PropertyScalarOrNullToValue(
					(string) $property,
					$value
				);
			},
			$data_keys,
			$data
		));

		$object = new $object_type($object_args);

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage(
			'Object could not be found for the specified id!'
		);

		$repo->RecallTypedObject($object->ObtainId());
	}

	/**
	* @template K as key-of<S>
	*
	* @dataProvider dataProviderAppendTypedObject
	*
	* @depends testAppendTypedObject
	*
	* @param class-string<AppendableTypedObjectRepository> $repo_type
	* @param array{type:class-string<T1>} $repo_args
	* @param list<S> $_append_these
	* @param list<S2> $expect_these
	*/
	public function testCustomFailure(
		string $repo_type,
		array $repo_args,
		array $_append_these,
		array $expect_these
	) : void {
		$repo = new $repo_type(
			$repo_args
		);

		$object_type = $repo_args['type'];

		$data = current($expect_these);

		/**
		* @var array<int, K>
		*/
		$data_keys = array_keys($data);

		/**
		* @var T
		*/
		$object_args = array_combine($data_keys, array_map(
			/**
			* @param K $property
			* @param S[K] $value
			*
			* @return scalar|array|object|null
			*/
			function ($property, $value) use ($object_type) {
				/**
				* @var scalar|array|object|null
				*/
				return $object_type::PropertyScalarOrNullToValue(
					(string) $property,
					$value
				);
			},
			$data_keys,
			$data
		));

		$object = new $object_type($object_args);

		$random = bin2hex(random_bytes(16));

		$this->expectException(Exception::class);
		$this->expectExceptionMessage($random);

		$repo->RecallTypedObject($object->ObtainId(), new Exception($random));
	}

	/**
	* @return array<
		int,
		array{
			0:class-string<AppendableTypedObjectRepository&PatchableObjectRepository>,
			1:array{type:class-string<T1>},
			2:array<string, scalar|null>,
			3:array<string, scalar|null>,
			4:array<string, scalar|null>
		}
	>
	*/
	public function dataProviderPatchObject() : array
	{
		/**
		* @var array<
			int,
			array{
				0:class-string<AppendableTypedObjectRepository&PatchableObjectRepository>,
				1:array{type:class-string<T1>},
				2:array<string, scalar|null>,
				3:array<string, scalar|null>,
				4:array<string, scalar|null>
			}
		>
		*/
		return [
			[
				Fixtures\DaftTypedObjectMemoryRepository::class,
				[
					'type' => Fixtures\MutableForRepository::class,
				],
				[
					'id' => 0,
					'name' => 'foo',
				],
				[
					'name' => 'bar',
				],
				[
					'id' => '1',
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
	* @depends testAppendTypedObject
	*
	* @param class-string<AppendableTypedObjectRepository&PatchableObjectRepository> $repo_type
	* @param array{type:class-string<T1>} $repo_args
	* @param array<string, scalar|null> $append_this
	* @param array<string, scalar|null> $patch_this
	* @param array<string, scalar|null> $expect_this
	*/
	public function testPatchObject(
		string $repo_type,
		array $repo_args,
		array $append_this,
		array $patch_this,
		array $expect_this
	) : void {
		$repo = new $repo_type(
			$repo_args
		);

		$object_type = $repo_args['type'];

		$object = $object_type::__fromArray($append_this);

		$fresh = $repo->AppendTypedObject($object);

		$repo->PatchTypedObjectData($fresh->ObtainId(), $patch_this);

		$this->assertSame(
			$expect_this,
			$repo->RecallTypedObject($fresh->ObtainId())->__toArray()
		);
	}
}
