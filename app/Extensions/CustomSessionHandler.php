<?php

namespace App\Extensions;

use App\Events\SessionExpired;
use Illuminate\Session\FileSessionHandler;
use Symfony\Component\Finder\Finder;

class CustomSessionHandler extends FileSessionHandler
{
    /**
     * {@inheritdoc}
     */
    public function forceRead($sessionId)
    {
        if ($this->files->isFile($path = $this->path.'/'.$sessionId)) {
            return $this->files->sharedGet($path);
        }

        return '';
    }

    public function getLoggedUserFromSession($sessionId)
    {
        $data = @unserialize($this->forceRead($sessionId));
        return $data[auth()->guard()->getName()] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime)
    {
        $files = Finder::create()
            ->in($this->path)
            ->files()
            ->ignoreDotFiles(true)
            ->date('<= now - '.$lifetime.' seconds');

        foreach ($files as $file) {
            if($userId = $this->getLoggedUserFromSession($file->getFilenameWithoutExtension())){
                event(new SessionExpired($userId));
            }

            $this->files->delete($file->getRealPath());
        }
    }
}
