<?php
/**
 * QoS Bandwidth Throttler (part of Lotos Framework)
 *
 * Copyright (c) 2005-2010 Artur Graniszewski (aargoth@boo.pl) 
 * All rights reserved.
 */ 

class DownloadLimit
{
    // rata burst in bytes/secunda
	public $burstLimit = 80000;
	// dupa aceasta perioada de timp se utilizeaza rata standard
	public $burstTimeout = 20;
	// rata standard de transfer a datelor
	public $rateLimit = 10000;
	// activeaza / dezactiveaza modulul
	public $enabled = false;
	// timpul de sfarsit al aplicatiei
	protected $lastHeartBeat = 0;
	// timpul de inceput al aplicatiei
	protected $firstHeartBeat = 0;
	// numar de bytes deja transmisi
	protected $bytesSent = 0;
	// timpul total de trasnfer a datelor in microsecunde
	protected $sendingTime = 0;
	// limita curenta de trasnfer a datelor (in bytes / secunda)
	protected $currentLimit = 0;
	// este acesta ultimul pachet care trebuie trimis utilizatorului
    protected $isFinishing = false;
	
    // Constructorul
	public function __construct($burstLimit = 80000, $burstTimeout = 20, $rateLimit = 10000, $enabled = false) 
    {
        $this->burstLimit = $burstLimit;
        $this->burstTimeout = $burstTimeout;
        $this->rateLimit = $rateLimit;
        $this->enabled = $enabled;
        // se dezactiveaza functia de compresie gzip pentru a nu altera rata de transfer
		if(function_exists('apache_setenv')) {
            apache_setenv('no-gzip', '1');
		}
		// se dezactiveaza timeout-ul scriptului
		if(false === strpos(ini_get('disable_functions'), 'set_time_limit')) {
			@set_time_limit(0);
		}
		// seteaza rata burst ca si rata curenta de transfer
		$this->currentLimit = $this->burstLimit;
		// seteaza callback-ul ce va fi afisat
		ob_start(array($this, 'onFlush'), $this->rateLimit);
	}
	// Destructorul
	public function __destruct() {
		$this->isFinishing = true;
	}
	// Mecanismul de limitare a vitezei de download
	public function onFlush(&$buffer) 
    {
		if($buffer === "" || $this->isFinishing) {
			return $buffer;
		}	
		// realizeaza un cache
		$bufferLength = strlen($buffer);
		// intoarce timpul curent all sistemului
		$now = microtime(true);
        // initiliazare
		if($this->lastHeartBeat === 0) {
			$this->lastHeartBeat = $this->firstHeartBeat = $now;
		}
		// se calculeaza cantitatea de informatie ce va fi trimisa catre utilizator
		$usleep = $bufferLength / $this->currentLimit;
		if($usleep > 0) {
            usleep($usleep * 1000000);
			$this->sendingTime += $usleep;
		}
        // se verifica daca rata de burst este activa si daca trebuie il trecem pe off
		if($this->currentLimit === $this->burstLimit && $this->burstLimit !== $this->rateLimit) {
            if($now > ($this->firstHeartBeat + $this->burstTimeout)) {
                $this->currentLimit = $this->rateLimit;
            }
		}
		// updateaza statisticile sistemului       
		$this->bytesSent += $bufferLength;
		$this->lastHeartBeat = $now;
		// se returneaza buffer-ul
		return $buffer;
	}
}