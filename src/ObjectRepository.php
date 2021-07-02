<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace DaftFramework\RelaxedObjectRepository;

use Throwable;

/**
 * @template OBJECT as object
 * @template ID as array<string, scalar>
 * @template CTORARGS as array<string, scalar|array|object|null>
 */
interface ObjectRepository
{
	/**
	 * @param CTORARGS $_options
	 */
	public function __construct(array $_options);

	/**
	 * @param OBJECT $object
	 */
	public function UpdateObject(
		object $object
	) : void;

	/**
	 * @param ID $id
	 */
	public function ForgetObject(
		array $id
	) : void;

	/**
	 * @param ID $id
	 */
	public function RemoveObject(
		array $id
	) : void;

	/**
	 * @param ID $id
	 */
	public function RecallObject(
		array $id,
		Throwable $not_found = null
	) : object;

	/**
	 * @param ID $id
	 */
	public function MaybeRecallObject(
		array $id
	) : ? object;

	/**
	 * @param OBJECT $object
	 *
	 * @return ID
	 */
	public function ObtainIdFromObject(object $object) : array;
}
