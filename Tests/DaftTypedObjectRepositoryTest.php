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
	* @return array<
		int,
		array{
			0:class-string<T1>,
			1:class-string<AppendableTypedObjectRepository>,
			2:array<int, mixed>,
			3:array<int, array<string, scalar|null>>,
			4:array<int, array<string, scalar|null>>
		}
	>
	*/
	public function dataProviderAppendTypedObject() : array
	{
		/**
		* @var array<
			int,
			array{
				0:class-string<T1>,
				1:class-string<AppendableTypedObjectRepository>,
				2:array<int, mixed>,
				3:array<int, array<string, scalar|null>>,
				4:array<int, array<string, scalar|null>>
			}
		>
		*/
		return [
			[
				Fixtures\ImmutableForRepository::class,
				Fixtures\DaftTypedObjectMemoryRepository::class,
				[],
				[
					[
						'id' => 0,
						'name' => 'foo',
					],
				],
				[
					[
						'id' => 1,
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
	* @param class-string<T1> $object_type
	* @param class-string<AppendableTypedObjectRepository> $repo_type
	* @param array<int, mixed> $repo_args
	* @param array<int, S> $append_these
	* @param array<int, S2> $expect_these
	*/
	public function testAppendTypedObject(
		string $object_type,
		string $repo_type,
		array $repo_args,
		array $append_these,
		array $expect_these
	) : void {
		$repo = $repo_type::DaftTypedObjectRepositoryByType(
			$object_type,
			...$repo_args
		);

		$this->assertGreaterThan(0, count($append_these));
		$this->assertCount(count($append_these), $expect_these);

		/**
		* @var array<int, T1>
		*/
		$testing = [];

		foreach ($append_these as $i => $data) {
			/**
			* @var array<int, K>
			*/
			$data_keys = array_keys($data);

			/**
			* @var T1
			*/
			$object = new $object_type(array_combine($data_keys, array_map(
				/**
				* @param K $property
				* @param S[K] $value
				*
				* @return T[K]
				*/
				function (string $property, $value) use ($object_type) {
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
			)));

			$testing[$i] = $repo->AppendTypedObject($object);
		}

		/**
		* @var array<int, string>
		*/
		$properties = $object_type::TYPED_PROPERTIES;

		foreach ($testing as $i => $object) {
			$this->assertSame(
				$expect_these[$i],
				array_combine($properties, array_map(
					/**
					* @return S2[K]
					*/
					function (string $property) use ($object) {
						/**
						* @var T[K]
						*/
						$value = $object->$property;

						/**
						* @var S2[K]
						*/
						return $object::PropertyValueToScalarOrNull(
							$property,
							$value
						);
					},
					$properties
				))
			);

			$this->assertSame(
				$object,
				$repo->RecallTypedObject($object->ObtainId())
			);

			$repo->ForgetTypedObject($object->ObtainId());

			/**
			* @var T1|null
			*/
			$fresh1 = $repo->MaybeRecallTypedObject($object->ObtainId());

			$this->assertNotNull($fresh1);

			/**
			* @var T1
			*/
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

		/**
		* @var S
		*/
		$data = current($expect_these);

		/**
		* @var array<int, K>
		*/
		$data_keys = array_keys($data);

		/**
		* @var T1
		*/
		$object = new $object_type(array_combine($data_keys, array_map(
			/**
			* @param K $property
			* @param S[K] $value
			*
			* @return T[K]
			*/
			function (string $property, $value) use ($object_type) {
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
		)));

		/**
		* @var T1
		*/
		$fresh1 = $repo->AppendTypedObject($object);

		$this->assertNotSame($object, $fresh1);
		$this->assertSame($object->ObtainId(), $fresh1->ObtainId());

		/**
		* @var T1
		*/
		$fresh2 = $repo->AppendTypedObject($object);

		$this->assertNotSame($object, $fresh2);
		$this->assertNotSame($fresh1, $fresh2);
		$this->assertNotSame($fresh1->ObtainId(), $fresh2->ObtainId());
	}

	/**
	* @template K as key-of<S>
	*
	* @dataProvider dataProviderAppendTypedObject
	*
	* @depends testAppendTypedObject
	*
	* @param class-string<T1> $object_type
	* @param class-string<AppendableTypedObjectRepository> $repo_type
	* @param array<int, mixed> $repo_args
	* @param array<int, S> $_append_these
	* @param array<int, S2> $expect_these
	*/
	public function testDefaultFailure(
		string $object_type,
		string $repo_type,
		array $repo_args,
		array $_append_these,
		array $expect_these
	) : void {
		$repo = $repo_type::DaftTypedObjectRepositoryByType(
			$object_type,
			...$repo_args
		);

		$data = current($expect_these);

		/**
		* @var array<int, K>
		*/
		$data_keys = array_keys($data);

		$object = new $object_type(array_combine($data_keys, array_map(
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
		)));

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
	* @param class-string<T1> $object_type
	* @param class-string<AppendableTypedObjectRepository> $repo_type
	* @param array<int, mixed> $repo_args
	* @param array<int, S> $_append_these
	* @param array<int, S2> $expect_these
	*/
	public function testCustomFailure(
		string $object_type,
		string $repo_type,
		array $repo_args,
		array $_append_these,
		array $expect_these
	) : void {
		$repo = $repo_type::DaftTypedObjectRepositoryByType(
			$object_type,
			...$repo_args
		);

		$data = current($expect_these);

		/**
		* @var array<int, K>
		*/
		$data_keys = array_keys($data);

		$object = new $object_type(array_combine($data_keys, array_map(
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
		)));

		$random = bin2hex(random_bytes(16));

		$this->expectException(Exception::class);
		$this->expectExceptionMessage($random);

		$repo->RecallTypedObject($object->ObtainId(), new Exception($random));
	}
}
