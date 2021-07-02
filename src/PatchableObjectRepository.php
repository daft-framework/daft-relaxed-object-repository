<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository;

/**
 * @template TYPE as object
 * @template ID as array<string, scalar>
 * @template PARTIAL as array<string, scalar|array|object|null>
 * @template CTORARGS as array<string, scalar|array|object|null>
 *
 * @template-extends ObjectRepository<TYPE, ID, CTORARGS>
 */
interface PatchableObjectRepository extends ObjectRepository
{
	/**
	 * @param ID $id
	 * @param PARTIAL $data
	 */
	public function PatchObjectData(array $id, array $data) : void;
}
