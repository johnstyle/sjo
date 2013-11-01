<?php

namespace PHPTools\Log;

/**
 * Décris une instance logger-aware
 */
interface LoggerAwareInterface
{
    /**
     * Définit une instance logger sur l'objet
     *
     * @param LoggerInterface $logger
     * @return null
     */
    public function setLogger(LoggerInterface $logger);
}
