<?php
/**
* @author SignpostMarv
*/
declare(strict_types=1);

namespace SignpostMarv\DaftTypedObject;

/**
* @template T1 as DaftTypedObjectForRepository
*/
interface AppendableTypedObjectRepository extends DaftTypedObjectRepository
{
	/**
	* @param T1 $object
	*
	* @return T1
	*/
	public function AppendTypedObject(
		DaftTypedObjectForRepository $object
	) : DaftTypedObjectForRepository;
}
