<?php

class DatabaseException extends MoonDragonException {}

class BadConnectionException extends DatabaseException {}

class QueryException extends DatabaseException {}

class EmptyResultException extends DatabaseException {}
