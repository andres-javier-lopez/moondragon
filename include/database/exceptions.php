<?php

class DatabaseException extends MoonDragonException {}

class BadConnectionException extends DatabaseException {}

class QueryException extends DatabaseException {}

class EmptyResultException extends DatabaseException {}

class ReadException extends QueryException {}

class CreateException extends QueryException {}

class DeleteException extends QueryException {}

class UpdateException extends QueryException {}