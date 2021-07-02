<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository\Fixtures;

/**
 * @template OBJECT as Thing
 * @template ID as array{id:int}
 * @template PARTIAL as array{name:string}
 * @template SIMPLE as array{id:int, name:string}
 * @template CTORARGS as array<string, scalar|array|object|null>
 *
 * @template-extends MemoryRepository<OBJECT, ID, SIMPLE, PARTIAL, CTORARGS>
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
	 * @param OBJECT $object
	 *
	 * @return OBJECT
	 */
	public function AppendObject(
		object $object
	) : object {
		/** @var PARTIAL */
		$data = [
			'name' => $object->name,
		];

		/**
		 * @var OBJECT
		 */
		return $this->AppendObjectFromArray($data);
	}

	/**
	 * @param SIMPLE $array
	 *
	 * @return OBJECT
	 */
	public function ConvertSimpleArrayToObject(array $array) : object
	{
		/** @var OBJECT */
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
