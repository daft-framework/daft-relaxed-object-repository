<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\DaftRelaxedObjectRepository\Fixtures;

use RuntimeException;
use SignpostMarv\DaftRelaxedObjectRepository\AbstractObjectRepository;
use SignpostMarv\DaftRelaxedObjectRepository\AppendableObjectRepository;
use SignpostMarv\DaftRelaxedObjectRepository\ConvertingRepository;
use SignpostMarv\DaftRelaxedObjectRepository\PatchableObjectRepository;
use Throwable;

/**
 * @template T1 as object
 * @template T2 as array{id:int}
 * @template S1 as array{name:string}
 * @template S2 as array{id:int, name:string}
 *
 * @template-extends AbstractObjectRepository<T1, T2>
 *
 * @template-implements AppendableObjectRepository<T1, T2, S1>
 * @template-implements ConvertingRepository<T1, S2, T2>
 * @template-implements PatchableObjectRepository<T1, T2, S1>
 */
abstract class MemoryRepository extends AbstractObjectRepository implements
		AppendableObjectRepository,
		ConvertingRepository,
		PatchableObjectRepository
{
	/**
	 * @var array<string, S2>
	 */
	protected array $data = [];

	/**
	 * @var array<string, T1>
	 */
	protected array $memory = [];

	public function AppendObjectFromArray(
		array $data
	) : object {
		$new_id = max(0, count($this->data)) + 1;

		/**
		 * @var S2
		 */
		$data = [
			'id' => $new_id,
			'name' => $data['name'],
		];

		$hash = static::RelaxedObjectHash(['id' => $new_id]);

		$this->data[$hash] = $data;

		$object = $this->ConvertSimpleArrayToObject($data);

		$this->memory[$hash] = $object;

		/**
		 * @var T1
		 */
		return $object;
	}

	/**
	 * @param T1 $object
	 */
	public function UpdateObject(
		object $object
	) : void {
		$id = $this->ObtainIdFromObject($object);

		$hash = static::RelaxedObjectHash($id);

		parent::UpdateObject($object);

		$this->data[$hash] = $this->ConvertObjectToSimpleArray($object);
	}

	/**
	 * @param T2 $id
	 */
	public function RemoveObject(array $id) : void
	{
		$hash = static::RelaxedObjectHash($id);

		$this->ForgetObject($id);
		unset($this->data[$hash]);
	}

	/**
	 * @param T2 $id
	 *
	 * @return T1|null
	 */
	public function MaybeRecallObject(
		array $id
	) : ? object {
		$maybe = parent::MaybeRecallObject($id);

		if (is_null($maybe)) {
			$hash = static::RelaxedObjectHash($id);

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
	 * @param T2 $id
	 * @param S1 $data
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

		/** @var S2 */
		$from_array_args = $id + $data;

		$object = $this->ConvertSimpleArrayToObject($from_array_args);

		$this->UpdateObject($object);
	}

	/**
	 * @param T1 $object
	 *
	 * @return T2
	 */
	public function ObtainIdFromObject(object $object) : array
	{
		/** @var T2 */
		return [
			'id' => $object->id,
		];
	}
}
