<?php

namespace Github\Exception;

/**
 * InvalidArgumentException.
 *
 * @author Joseph Bielawski <stloyd@gmail.com>
 */
// Client and (wrong) Server in Contents.php
class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
}
