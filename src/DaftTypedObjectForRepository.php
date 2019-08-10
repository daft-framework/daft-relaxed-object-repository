<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\DaftTypedObject;

/**
* @template T as array<string, scalar>
*/
interface DaftTypedObjectForRepository extends DaftTypedObject
{
	/**
	* @return T
	*/
	public function ObtainId() : array;
}
