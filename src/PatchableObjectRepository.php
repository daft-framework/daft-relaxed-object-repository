<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\DaftRelaxedObjectRepository;

/**
 * @template T1 as object
 * @template T2 as array<string, scalar>
 * @template T3 as array<string, scalar|null>
 *
 * @template-extends ObjectRepository<T1, T2>
 */
interface PatchableObjectRepository extends ObjectRepository
{
	/**
	 * @param T2 $id
	 * @param T3 $data
	 */
	public function PatchObjectData(array $id, array $data) : void;
}
