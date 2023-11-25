<?php

namespace MasterStudy\Lms\Routing\Swagger\Routes\Comment;

use MasterStudy\Lms\Routing\Swagger\Fields\Comment;
use MasterStudy\Lms\Routing\Swagger\ResponseInterface;
use MasterStudy\Lms\Routing\Swagger\Route;

final class Unspam extends Route implements ResponseInterface {

	/**
	 * Response Schema Properties
	 * @return array
	 */
	public function response(): array {
		return array(
			'comment' => Comment::as_object(),
		);
	}

	/**
	 * Route Summary
	 * @return string
	 */
	public function get_summary(): string {
		return 'Unspam comment';
	}

	/**
	 * Route Description
	 * @return string
	 */
	public function get_description(): string {
		return 'Unspam comment';
	}
}
