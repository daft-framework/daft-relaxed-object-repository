<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\DaftTypedObject;

use RuntimeException;
use Throwable;

/**
* @template T1 as DaftTypedObjectForRepository
* @template T2 as array<string, scalar>
*
* @template-implements DaftTypedObjectRepository<T1, T2>
*/
abstract class AbstractDaftTypedObjectRepository implements DaftTypedObjectRepository
{
	/**
	* @var array<string, T1>
	*/
	protected $memory = [];

	/**
	* @var class-string<T1>
	*/
	protected $type;

	/**
	* @param class-string<T1> $type
	*/
	protected function __construct(string $type)
	{
		$this->type = $type;
	}

	/**
	* @param T1 $object
	*/
	public function UpdateTypedObject(
		DaftTypedObjectForRepository $object
	) : void {
		$hash = static::DaftTypedObjectHash($object->ObtainId());

		$this->memory[$hash] = $object;
	}

	/**
	* @param T2 $id
	*/
	public function ForgetTypedObject(array $id) : void
	{
		$hash = static::DaftTypedObjectHash($id);

		unset($this->memory[$hash]);
	}

	/**
	* @param T2 $id
	*
	* @return T1|null
	*/
	public function MaybeRecallTypedObject(
		array $id
	) : ? DaftTypedObjectForRepository {
		$hash = static::DaftTypedObjectHash($id);

		/**
		* @var T1|null
		*/
		return $this->memory[$hash] ?? null;
	}

	/**
	* @param T2 $id
	*/
	public function RecallTypedObject(
		array $id,
		Throwable $not_found = null
	) : DaftTypedObjectForRepository {
		$maybe = $this->MaybeRecallTypedObject($id);

		if (is_null($maybe)) {
			throw $not_found ?: new RuntimeException(
				'Object could not be found for the specified id!'
			);
		}

		return $maybe;
	}

	/**
	* @param class-string<T1> $type
	* @param mixed ...$_args
	*
	* @return static<T1, T2>
	*/
	public static function DaftTypedObjectRepositoryByType(
		string $type,
		...$_args
	) : DaftTypedObjectRepository {
		/**
		* @var static<T1, T2>
		*/
		return new static($type);
	}

	/**
	* @param T2 $id
	*/
	protected static function DaftTypedObjectHash(
		array $id
	) : string {
		return hash('sha512', json_encode($id), true);
	}
}
