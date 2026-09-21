<?php
namespace FreePBX\modules\Backup\Json;

function json_decode($json, $assoc = false, $depth = 512, $options = 0) {
	$data = \json_decode($json, $assoc, $depth, $options);
	if (JSON_ERROR_NONE !== json_last_error()) {
		return null;
	}
	return $data;
}

/**
 * Decode backup archive content (manifest, astdb, legacy metadata).
 * JSON arrays are preferred. Serialized input must decode to an array.
 * PHP 7+ passes allowed_classes false so payload classes are not constructed.
 * PHP 5.6 rejects a serialized object (O: or C:) before calling unserialize().
 *
 * @param mixed $raw
 * @return array|bool
 */
function safe_unserialize($raw) {
	if (!is_string($raw) || $raw === '') {
		return false;
	}

	$decoded = \json_decode($raw, true);
	if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
		return $decoded;
	}

	// unserialize() options exist only on PHP 7.0+.
	if (PHP_VERSION_ID >= 70000) {
		$data = unserialize($raw, array('allowed_classes' => false));
	} else {
		// O:<len>:"ClassName" or C:<len>:"ClassName", including nested after ; { or }
		if (preg_match('/(^|[;{}])[OC]:\d+:"/', $raw)) {
			return false;
		}
		$data = @unserialize($raw);
	}

	// Reject incomplete objects left behind by allowed_classes=false,
	// and bail out of cyclic references. Manifests are shallow.
	$hasObject = function ($value, $depth) use (&$hasObject) {
		if ($depth > 256 || is_object($value)) {
			return true;
		}
		if (!is_array($value)) {
			return false;
		}
		foreach ($value as $item) {
			if ($hasObject($item, $depth + 1)) {
				return true;
			}
		}
		return false;
	};

	if (!is_array($data) || $hasObject($data, 0)) {
		return false;
	}

	return $data;
}