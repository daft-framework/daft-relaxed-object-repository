<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository;

/**
 * @template OBJECT as object
 * @template ID as array<string, scalar>
 * @template PARTIAL as array<string, scalar|array|object|null>
 * @template CTORARGS as array<string, scalar|array|object|null>
 *
 * @template-extends ObjectRepository<OBJECT, ID, CTORARGS>
 */
interface AppendableObjectRepository extends ObjectRepository
{
	/**
	 * @param OBJECT $object
	 *
	 * @return OBJECT
	 */
	public function AppendObject(
		object $object
	) : object;

	/**
	 * @param PARTIAL $data
	 *
	 * @return OBJECT
	 */
	public function AppendObjectFromArray(
		array $data
	) : object;
}
