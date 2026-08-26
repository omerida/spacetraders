CREATE TABLE IF NOT EXISTS registry_text (
    name TEXT PRIMARY KEY UNIQUE,
    val TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER IF NOT EXISTS update_registry_text_timestamp
AFTER UPDATE ON registry_text
BEGIN
    UPDATE registry_text
    SET updated_at = CURRENT_TIMESTAMP
    WHERE name = OLD.name;
END;

CREATE TABLE IF NOT EXISTS registry_int (
    name TEXT PRIMARY KEY UNIQUE,
    val INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER IF NOT EXISTS update_registry_int_timestamp
AFTER UPDATE ON registry_int
BEGIN
    UPDATE registry_int
    SET updated_at = CURRENT_TIMESTAMP
    WHERE name = OLD.name;
END;

CREATE TABLE IF NOT EXISTS registry_bool (
    name TEXT PRIMARY KEY UNIQUE,
    val BOOL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER IF NOT EXISTS update_registry_bool_timestamp
AFTER UPDATE ON registry_bool
BEGIN
    UPDATE registry_bool
    SET updated_at = CURRENT_TIMESTAMP
    WHERE name = OLD.name;
END;

CREATE TABLE IF NOT EXISTS event_record (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    data CLOB DEFAULT NULL,
    name VARCHAR(512) NOT NULL,
    source VARCHAR(512) NOT NULL,
    description CLOB DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS market_trade_goods_activity (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    symbol VARCHAR(256) NOT NULL,
    type VARCHAR(256) NOT NULL,
    supply VARCHAR(256) NOT NULL,
    activity VARCHAR(256) NULL,
    tradeVolume INTEGER NOT NULL,
    purchasePrice INTEGER NOT NULL,
    sellPrice INTEGER NOT NULL
);