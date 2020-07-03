<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200703092648 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, orders_id INT DEFAULT NULL, quantity INT NOT NULL, unit_price NUMERIC(10, 2) NOT NULL, ammount NUMERIC(10, 2) NOT NULL, INDEX IDX_BA388B74584665A (product_id), INDEX IDX_BA388B7CFFE9AD6 (orders_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3AF34668989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, product_id INT NOT NULL, comment VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_5F9E962AA76ED395 (user_id), INDEX IDX_5F9E962A4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE flavors (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_77B47FD2989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, ref VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, status VARCHAR(255) NOT NULL, amount NUMERIC(10, 2) NOT NULL, delivery_point VARCHAR(255) NOT NULL, payment VARCHAR(255) DEFAULT NULL, INDEX IDX_E52FFDEEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, volume_id INT DEFAULT NULL, category_id INT DEFAULT NULL, flavor_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, degree NUMERIC(4, 2) NOT NULL, price NUMERIC(10, 2) NOT NULL, quantity INT NOT NULL, image VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B3BA5A5A989D9B62 (slug), INDEX IDX_B3BA5A5A8FD80EEA (volume_id), INDEX IDX_B3BA5A5A12469DE2 (category_id), INDEX IDX_B3BA5A5AFDDA6450 (flavor_id), FULLTEXT INDEX search (name, description), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, postal_code VARCHAR(5) NOT NULL, city VARCHAR(255) NOT NULL, phone VARCHAR(10) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role JSON DEFAULT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volumes (id INT AUTO_INCREMENT NOT NULL, volume INT NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7ADCAA15989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B74584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A8FD80EEA FOREIGN KEY (volume_id) REFERENCES volumes (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AFDDA6450 FOREIGN KEY (flavor_id) REFERENCES flavors (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A12469DE2');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5AFDDA6450');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7CFFE9AD6');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B74584665A');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A4584665A');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA76ED395');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEA76ED395');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A8FD80EEA');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE flavors');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE volumes');
    }
}
