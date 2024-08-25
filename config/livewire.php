<?php

return [

'temporary_file_upload' => [
    'rules' => 'file|mimes:png,jpg|max:102400', // (100MB max, and only accept PNGs, JPEGs)
],

'temporary_file_upload' => [
    'directory' => 'livewire-tmp',
],

];