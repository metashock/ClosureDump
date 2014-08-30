<?php

/**
 * Debugging helper that dumps the source code 
 * of a closure at runtime.
 *
 */
class ClosureDump
{

	/**
	 * Dumps the declaration (source code) of a Closure.
	 *
	 * @param Closure $closure The closure
	 * @return string
	 */
	public static function dump(Closure $closure) {
		$str = 'function (';
		$r = new ReflectionFunction($c);
		$params = array();
		foreach($r->getParameters() as $p) {
			$s = '';
			if($p->isArray()) {
				$s .= 'array ';
			} else if($p->getClass()) {
				$s .= $p->getClass()->name . ' ';
			}
			if($p->isPassedByReference()){
				$s .= '&';
			}
			$s .= '$' . $p->name;
			if($p->isOptional()) {
				$s .= ' = ' . var_export($p->getDefaultValue(), TRUE);
			}
			$params []= $s;
		}
		$str .= implode(', ', $params);
		$str .= '){' . PHP_EOL;
		$lines = file($r->getFileName());
		for($l = $r->getStartLine(); $l < $r->getEndLine(); $l++) {
			$str .= $lines[$l];
		}
		return $str;
	}

}
