ALTER TABLE pro3x_invoices ADD tenderDate DATETIME DEFAULT NULL;

ALTER TABLE pro3x_invoices ADD tenderTemplate_id INT DEFAULT NULL;
ALTER TABLE pro3x_invoices ADD CONSTRAINT FK_4D4A661E941B5291 FOREIGN KEY (tenderTemplate_id) REFERENCES pro3x_templates (id);
CREATE INDEX IDX_4D4A661E941B5291 ON pro3x_invoices (tenderTemplate_id);

update pro3x_invoices
set tenderTemplate_id = 6,
    template_id = null,
    tenderDate = created
where tenderSequence is not null;