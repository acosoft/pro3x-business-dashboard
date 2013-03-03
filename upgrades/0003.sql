ALTER TABLE pro3x_customer_notes ADD createdBy_id INT DEFAULT NULL;
ALTER TABLE pro3x_customer_notes ADD CONSTRAINT FK_DF405AC03174800F FOREIGN KEY (createdBy_id) REFERENCES pro3x_users (id);
CREATE INDEX IDX_DF405AC03174800F ON pro3x_customer_notes (createdBy_id);

ALTER TABLE pro3x_customer_notes ADD noteType VARCHAR(255) DEFAULT NULL;

ALTER TABLE pro3x_clients ADD image VARCHAR(255) DEFAULT NULL;