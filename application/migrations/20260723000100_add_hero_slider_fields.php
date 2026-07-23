<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_hero_slider_fields extends CI_Migration
{
	private $table = 'image_slider';

	public function up()
	{
		$this->load->dbforge();

		$fields = [
			'mobile_image' => [
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => true,
			],
			'primary_button_text' => [
				'type' => 'VARCHAR',
				'constraint' => 100,
				'null' => true,
			],
			'primary_button_url' => [
				'type' => 'VARCHAR',
				'constraint' => 500,
				'null' => true,
			],
			'secondary_button_text' => [
				'type' => 'VARCHAR',
				'constraint' => 100,
				'null' => true,
			],
			'secondary_button_url' => [
				'type' => 'VARCHAR',
				'constraint' => 500,
				'null' => true,
			],
			'text_position' => [
				'type' => 'VARCHAR',
				'constraint' => 20,
				'null' => false,
				'default' => 'left',
			],
			'overlay_opacity' => [
				'type' => 'TINYINT',
				'constraint' => 3,
				'unsigned' => true,
				'null' => false,
				'default' => 40,
			],
			'sort_order' => [
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => true,
				'null' => false,
				'default' => 0,
			],
			'updated_at' => [
				'type' => 'DATETIME',
				'null' => true,
			],
		];

		foreach ($fields as $name => $definition) {
			if (!$this->db->field_exists($name, $this->table)) {
				$this->dbforge->add_column($this->table, [$name => $definition]);
			}
		}
	}

	public function down()
	{
		$this->load->dbforge();

		foreach ([
			'mobile_image',
			'primary_button_text',
			'primary_button_url',
			'secondary_button_text',
			'secondary_button_url',
			'text_position',
			'overlay_opacity',
			'sort_order',
			'updated_at',
		] as $name) {
			if ($this->db->field_exists($name, $this->table)) {
				$this->dbforge->drop_column($this->table, $name);
			}
		}
	}
}
