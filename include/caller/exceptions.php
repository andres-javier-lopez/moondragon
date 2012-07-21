<?php

class CallerException extends MoonDragonException {}

class BadApiUrlException extends CallerException {}

class EmptyDataException extends CallerException {}

class CurlException extends CallerException {}

class JsonException extends CallerException {}
