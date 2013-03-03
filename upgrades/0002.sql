ALTER TABLE pro3x_clients ADD otherAccomodation VARCHAR(255) DEFAULT NULL, ADD otherOwnership VARCHAR(255) DEFAULT NULL, ADD birthday DATETIME DEFAULT NULL;
ALTER TABLE pro3x_invoices CHANGE sequence sequence INT DEFAULT NULL;

