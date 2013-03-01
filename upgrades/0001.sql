ALTER TABLE pro3x_invoices ADD tenderDate DATETIME DEFAULT NULL;

ALTER TABLE pro3x_invoices ADD tenderTemplate_id INT DEFAULT NULL;
ALTER TABLE pro3x_invoices ADD CONSTRAINT FK_4D4A661E941B5291 FOREIGN KEY (tenderTemplate_id) REFERENCES pro3x_templates (id);
CREATE INDEX IDX_4D4A661E941B5291 ON pro3x_invoices (tenderTemplate_id);

update pro3x_invoices
set tenderTemplate_id = 7,
    template_id = null,
    tenderDate = created
where tenderSequence is not null
    and sequence is null;

update pro3x_invoices
set tenderTemplate_id = 7,
    tenderDate = created
where tenderSequence is not null
    and sequence is not null;

ALTER TABLE pro3x_products ADD inputTaxRate_id INT DEFAULT NULL;
ALTER TABLE pro3x_products ADD CONSTRAINT FK_94DF13D18625605E FOREIGN KEY (inputTaxRate_id) REFERENCES pro3x_tax_rates (id);
CREATE INDEX IDX_94DF13D18625605E ON pro3x_products (inputTaxRate_id);

ALTER TABLE pro3x_products ADD inputPrice NUMERIC(14, 6) NOT NULL;
ALTER table pro3x_products ADD taxedInputPrice NUMERIC(14, 2) NOT NULL;

# - - - v2 - - - #

CREATE TABLE Receipt (id INT AUTO_INCREMENT NOT NULL, supplier_id INT DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_9C248FD92ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE ReceiptItem (id INT AUTO_INCREMENT NOT NULL, receipt_id INT DEFAULT NULL, product_id INT DEFAULT NULL, taxedInputPrice NUMERIC(14, 2) NOT NULL, amount NUMERIC(14, 2) NOT NULL, totalTaxedPrice NUMERIC(14, 2) NOT NULL, discount NUMERIC(14, 2) NOT NULL, discountAmount NUMERIC(14, 2) NOT NULL, totalTaxedDiscountPrice NUMERIC(14, 2) NOT NULL, totalTaxAmount NUMERIC(14, 2) NOT NULL, totalDiscountPrice NUMERIC(14, 2) NOT NULL, description VARCHAR(255) NOT NULL, inputTaxRate_id INT DEFAULT NULL, INDEX IDX_64D1E472B5CA896 (receipt_id), INDEX IDX_64D1E478625605E (inputTaxRate_id), INDEX IDX_64D1E474584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE Receipt ADD CONSTRAINT FK_9C248FD92ADD6D8C FOREIGN KEY (supplier_id) REFERENCES pro3x_clients (id);
ALTER TABLE ReceiptItem ADD CONSTRAINT FK_64D1E472B5CA896 FOREIGN KEY (receipt_id) REFERENCES Receipt (id);
ALTER TABLE ReceiptItem ADD CONSTRAINT FK_64D1E478625605E FOREIGN KEY (inputTaxRate_id) REFERENCES pro3x_tax_rates (id);
ALTER TABLE ReceiptItem ADD CONSTRAINT FK_64D1E474584665A FOREIGN KEY (product_id) REFERENCES pro3x_products (id);
ALTER TABLE pro3x_clients ADD type VARCHAR(255) NOT NULL;

ALTER TABLE pro3x_clients CHANGE `type` ctype VARCHAR(255) NOT NULL;

update pro3x_clients
set ctype = 'customer'
where ctype is null OR ctype = '';

CREATE TABLE RegistrationKey (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, customer_id INT DEFAULT NULL, validFrom DATETIME NOT NULL, validTo DATETIME NOT NULL, INDEX IDX_DF6499A44584665A (product_id), INDEX IDX_DF6499A49395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE RegistrationKey ADD CONSTRAINT FK_DF6499A44584665A FOREIGN KEY (product_id) REFERENCES pro3x_products (id);
ALTER TABLE RegistrationKey ADD CONSTRAINT FK_DF6499A49395C3F3 FOREIGN KEY (customer_id) REFERENCES pro3x_clients (id);
ALTER TABLE pro3x_clients CHANGE ctype discr VARCHAR(255) NOT NULL;

CREATE TABLE pro3x_customer_relations (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, related_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_4154A9057E3C61F9 (owner_id), INDEX IDX_4154A9054162C001 (related_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE pro3x_customer_relations ADD CONSTRAINT FK_4154A9057E3C61F9 FOREIGN KEY (owner_id) REFERENCES pro3x_clients (id);
ALTER TABLE pro3x_customer_relations ADD CONSTRAINT FK_4154A9054162C001 FOREIGN KEY (related_id) REFERENCES pro3x_clients (id);

CREATE TABLE pro3x_relation_type (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE pro3x_customer_relations ADD relationType_id INT DEFAULT NULL, DROP description;
ALTER TABLE pro3x_customer_relations ADD CONSTRAINT FK_4154A905E4E6521A FOREIGN KEY (relationType_id) REFERENCES pro3x_relation_type (id);
CREATE INDEX IDX_4154A905E4E6521A ON pro3x_customer_relations (relationType_id);

CREATE TABLE pro3x_customer_notes (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, created DATETIME NOT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_DF405AC09395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE pro3x_customer_notes ADD CONSTRAINT FK_DF405AC09395C3F3 FOREIGN KEY (customer_id) REFERENCES pro3x_clients (id);

ALTER TABLE pro3x_customer_notes CHANGE content content LONGTEXT NOT NULL;

ALTER TABLE pro3x_clients ADD accomodation VARCHAR(255) DEFAULT NULL, ADD ownership VARCHAR(255) DEFAULT NULL, ADD message LONGTEXT DEFAULT NULL, ADD warning LONGTEXT DEFAULT NULL;

INSERT INTO pro3x_relation_type(description) values('Otac'),('Sin'),('Majka'),('Kćer');