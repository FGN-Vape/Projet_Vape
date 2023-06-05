<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230604195551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX `primary` ON to_order');
        $this->addSql('ALTER TABLE to_order ADD date_id INT NOT NULL');
        $this->addSql('ALTER TABLE to_order ADD CONSTRAINT FK_FE15A2A8B897366B FOREIGN KEY (date_id) REFERENCES date (id)');
        $this->addSql('CREATE INDEX IDX_FE15A2A8B897366B ON to_order (date_id)');
        $this->addSql('ALTER TABLE to_order ADD PRIMARY KEY (user_id, product_id, date_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE to_order DROP FOREIGN KEY FK_FE15A2A8B897366B');
        $this->addSql('DROP INDEX IDX_FE15A2A8B897366B ON to_order');
        $this->addSql('DROP INDEX `PRIMARY` ON to_order');
        $this->addSql('ALTER TABLE to_order DROP date_id');
        $this->addSql('ALTER TABLE to_order ADD PRIMARY KEY (user_id, product_id)');
    }
}
