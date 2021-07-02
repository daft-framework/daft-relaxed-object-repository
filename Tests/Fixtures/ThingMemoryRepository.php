<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository\Fixtures;

/**
 * @template TYPE as Thing
 * @template ID as array{id:int}
 * @template PARTIAL as array{name:string}
 * @template SIMPLE as array{id:int, name:string}
 * @template CTORARGS as array<string, scalar|array|object|null>
 *
 * @template-extends MemoryRepository<TYPE, ID, SIMPLE, PARTIAL, CTORARGS>
 */
class ThingMemoryRepository extends MemoryRepository
{
	/**
	 * @param CTORARGS $options
	 */
	public function __construct(array $options)
	{
		parent::__construct($options);
	}

	/**
	 * @param TYPE $object
	 *
	 * @return TYPE
	 */
	public function AppendObject(
		object $object
	) : object {
		/** @var PARTIAL */
		$data = [
			'name' => $object->name,
		];

		/**
		 * @var TYPE
		 */
		return $this->AppendObjectFromArray($data);
	}

	/**
	 * @param SIMPLE $array
	 *
	 * @return TYPE
	 */
	public function ConvertSimpleArrayToObject(array $array) : object
	{
		/** @var TYPE */
		return new Thing($array['id'], $array['name']);
	}

	public function ConvertObjectToSimpleArray(object $object) : array
	{
		/** @var SIMPLE */
		return [
			'id' => $object->id,
			'name' => $object->name,
		];
	}
}
