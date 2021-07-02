<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository\Fixtures;

use function count;
use DaftFramework\RelaxedObjectRepository\AbstractObjectRepository;
use DaftFramework\RelaxedObjectRepository\AppendableObjectRepository;
use DaftFramework\RelaxedObjectRepository\ConvertingRepository;
use DaftFramework\RelaxedObjectRepository\PatchableObjectRepository;
use function is_null;

/**
 * @template TYPE as object
 * @template ID as array{id:positive-int}
 * @template SIMPLE as array<string, scalar|array|object|null>
 * @template PARTIAL as array<string, scalar|array|object|null>
 * @template CTORARGS as array<string, scalar|array|object|null>
 *
 * @template-extends AbstractObjectRepository<TYPE, ID, CTORARGS>
 *
 * @template-implements AppendableObjectRepository<TYPE, ID, PARTIAL, CTORARGS>
 * @template-implements ConvertingRepository<TYPE, SIMPLE, ID, CTORARGS>
 * @template-implements PatchableObjectRepository<TYPE, ID, PARTIAL, CTORARGS>
 */
abstract class MemoryRepository extends AbstractObjectRepository implements
		AppendableObjectRepository,
		ConvertingRepository,
		PatchableObjectRepository
{
	public const MIN_ID = 0;

	public const ID_INCREMENT = 1;

	/**
	 * @var array<string, SIMPLE>
	 */
	protected array $data = [];

	/**
	 * @var array<string, TYPE>
	 */
	protected array $memory = [];

	public function AppendObjectFromArray(
		array $data
	) : object {
		$new_id = max(self::MIN_ID, count($this->data)) + self::ID_INCREMENT;

		/** @var ID */
		$id = ['id' => $new_id];

		/**
		 * @var PARTIAL
		 */
		$data = [
			'name' => $data['name'],
		];

		$hash = $this->RelaxedObjectHash($id);

		/** @var SIMPLE */
		$data = $id + $data;

		$this->data[$hash] = $data;

		$object = $this->ConvertSimpleArrayToObject($data);

		$this->memory[$hash] = $object;

		/**
		 * @var TYPE
		 */
		return $object;
	}

	/**
	 * @param TYPE $object
	 */
	public function UpdateObject(
		object $object
	) : void {
		$id = $this->ObtainIdFromObject($object);

		$hash = $this->RelaxedObjectHash($id);

		parent::UpdateObject($object);

		$this->data[$hash] = $this->ConvertObjectToSimpleArray($object);
	}

	/**
	 * @param ID $id
	 */
	public function RemoveObject(array $id) : void
	{
		$hash = $this->RelaxedObjectHash($id);

		$this->ForgetObject($id);
		unset($this->data[$hash]);
	}

	/**
	 * @param ID $id
	 *
	 * @return TYPE|null
	 */
	public function MaybeRecallObject(
		array $id
	) : ? object {
		$maybe = parent::MaybeRecallObject($id);

		if (is_null($maybe)) {
			$hash = $this->RelaxedObjectHash($id);

			$row = $this->data[$hash] ?? null;

			if (null !== $row) {
				$object = $this->ConvertSimpleArrayToObject($row);

				$this->UpdateObject($object);

				return $object;
			}

			return null;
		}

		return $maybe;
	}

	/**
	 * @param ID $id
	 * @param PARTIAL $data
	 */
	public function PatchObjectData(array $id, array $data) : void
	{
		/**
		 * @var array<string, scalar|null>
		 */
		$id = $id;

		/**
		 * @var array<string, scalar|null>
		 */
		$data = $data;

		/** @var SIMPLE */
		$from_array_args = $id + $data;

		$object = $this->ConvertSimpleArrayToObject($from_array_args);

		$this->UpdateObject($object);
	}

	/**
	 * @param TYPE $object
	 *
	 * @return ID
	 */
	public function ObtainIdFromObject(object $object) : array
	{
		/** @var ID */
		return [
			'id' => $object->id,
		];
	}
}
