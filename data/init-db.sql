CREATE TABLE registry_text (
    name TEXT PRIMARY KEY UNIQUE,
    val TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER update_registry_text_timestamp
AFTER UPDATE ON registry_text
BEGIN
    UPDATE registry_text
    SET updated_at = CURRENT_TIMESTAMP
    WHERE name = OLD.name;
END;

CREATE TABLE registry_int (
    name TEXT PRIMARY KEY UNIQUE,
    val INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER update_registry_int_timestamp
AFTER UPDATE ON registry_int
BEGIN
    UPDATE registry_int
    SET updated_at = CURRENT_TIMESTAMP
    WHERE name = OLD.name;
END;

CREATE TABLE registry_bool (
    name TEXT PRIMARY KEY UNIQUE,
    val BOOL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER update_registry_bool_timestamp
AFTER UPDATE ON registry_bool
BEGIN
    UPDATE registry_bool
    SET updated_at = CURRENT_TIMESTAMP
    WHERE name = OLD.name;
END;