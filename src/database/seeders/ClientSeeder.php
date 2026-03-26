<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ContentEntry;
use App\Models\Section;
use App\Models\SectionField;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        // Cliente 1: Empresa de Servicios
        $client1 = Client::create([
            'name' => 'Tech Solutions',
            'slug' => 'tech-solutions',
            'domain' => 'techsolutions.com',
            'description' => 'Empresa de soluciones tecnológicas',
            'status' => 'active',
            'settings' => [
                'theme' => 'light',
                'language' => 'es',
            ],
        ]);

        // Usuario del cliente 1
        $user1 = User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan@techsolutions.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'client_id' => $client1->id,
            'is_active' => true,
        ]);

        $this->createSectionsForClient1($client1, $user1->id);

        // Cliente 2: Agencia Creativa
        $client2 = Client::create([
            'name' => 'Creative Studio',
            'slug' => 'creative-studio',
            'domain' => 'creativestudio.com',
            'description' => 'Agencia creativa digital',
            'status' => 'active',
            'settings' => [
                'theme' => 'dark',
                'language' => 'es',
            ],
        ]);

        // Usuario del cliente 2
        $user2 = User::create([
            'name' => 'María García',
            'email' => 'maria@creativestudio.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'client_id' => $client2->id,
            'is_active' => true,
        ]);

        $this->createSectionsForClient2($client2, $user2->id);

        $this->command->info('Clientes y usuarios de ejemplo creados');
    }

    private function createSectionsForClient1($client, $userId)
    {
        // Sección Nosotros (Single)
        $aboutSection = Section::create([
            'client_id' => $client->id,
            'name' => 'Nosotros',
            'slug' => 'nosotros',
            'description' => 'Información sobre la empresa',
            'type' => 'single',
            'sort_order' => 1,
            'is_visible' => true,
            'is_public_endpoint' => true,
            'endpoint_slug' => 'about',
        ]);

        // Campos de Nosotros
        SectionField::create(['section_id' => $aboutSection->id, 'name' => 'titulo', 'label' => 'Título', 'slug' => 'titulo', 'type' => 'text', 'is_required' => true, 'sort_order' => 1]);
        SectionField::create(['section_id' => $aboutSection->id, 'name' => 'contenido', 'label' => 'Contenido', 'slug' => 'contenido', 'type' => 'richtext', 'is_required' => true, 'sort_order' => 2]);
        SectionField::create(['section_id' => $aboutSection->id, 'name' => 'imagen', 'label' => 'Imagen', 'slug' => 'imagen', 'type' => 'image', 'sort_order' => 3]);

        // Crear entrada única
        $aboutEntry = ContentEntry::create([
            'section_id' => $aboutSection->id,
            'client_id' => $client->id,
            'title' => 'Sobre Nosotros',
            'slug' => 'sobre-nosotros',
            'status' => 'published',
            'created_by' => $userId,
            'updated_by' => $userId,
        ]);
        $aboutEntry->setFieldValue('titulo', 'Tech Solutions');
        $aboutEntry->setFieldValue('contenido', '<p>Somos una empresa dedicada a brindar soluciones tecnológicas innovadoras.</p>');

        // Sección Servicios (Collection)
        $servicesSection = Section::create([
            'client_id' => $client->id,
            'name' => 'Servicios',
            'slug' => 'servicios',
            'description' => 'Servicios que ofrecemos',
            'type' => 'collection',
            'sort_order' => 2,
            'is_visible' => true,
            'is_public_endpoint' => true,
            'endpoint_slug' => 'services',
        ]);

        // Campos de Servicios
        SectionField::create(['section_id' => $servicesSection->id, 'name' => 'titulo', 'label' => 'Título', 'slug' => 'titulo', 'type' => 'text', 'is_required' => true, 'sort_order' => 1]);
        SectionField::create(['section_id' => $servicesSection->id, 'name' => 'descripcion', 'label' => 'Descripción', 'slug' => 'descripcion', 'type' => 'textarea', 'is_required' => true, 'sort_order' => 2]);
        SectionField::create(['section_id' => $servicesSection->id, 'name' => 'icono', 'label' => 'Icono', 'slug' => 'icono', 'type' => 'text', 'sort_order' => 3]);
        SectionField::create(['section_id' => $servicesSection->id, 'name' => 'activo', 'label' => 'Activo', 'slug' => 'activo', 'type' => 'boolean', 'sort_order' => 4]);

        // Crear servicios de ejemplo
        $services = [
            ['titulo' => 'Desarrollo Web', 'descripcion' => 'Sitios web modernos y responsivos', 'icono' => 'globe', 'activo' => true],
            ['titulo' => 'Apps Móviles', 'descripcion' => 'Aplicaciones para iOS y Android', 'icono' => 'smartphone', 'activo' => true],
            ['titulo' => 'Consultoría IT', 'descripcion' => 'Asesoría en tecnología', 'icono' => 'briefcase', 'activo' => true],
        ];

        foreach ($services as $service) {
            $entry = ContentEntry::create([
                'section_id' => $servicesSection->id,
                'client_id' => $client->id,
                'title' => $service['titulo'],
                'slug' => \Illuminate\Support\Str::slug($service['titulo']),
                'status' => 'published',
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
            $entry->setFieldValue('titulo', $service['titulo']);
            $entry->setFieldValue('descripcion', $service['descripcion']);
            $entry->setFieldValue('icono', $service['icono']);
            $entry->setFieldValue('activo', $service['activo']);
        }
    }

    private function createSectionsForClient2($client, $userId)
    {
        // Sección Portafolio (Collection)
        $portfolioSection = Section::create([
            'client_id' => $client->id,
            'name' => 'Portafolio',
            'slug' => 'portafolio',
            'description' => 'Trabajos realizados',
            'type' => 'collection',
            'sort_order' => 1,
            'is_visible' => true,
            'is_public_endpoint' => true,
            'endpoint_slug' => 'portfolio',
        ]);

        // Campos
        SectionField::create(['section_id' => $portfolioSection->id, 'name' => 'titulo', 'label' => 'Título del Proyecto', 'slug' => 'titulo', 'type' => 'text', 'is_required' => true, 'sort_order' => 1]);
        SectionField::create(['section_id' => $portfolioSection->id, 'name' => 'descripcion', 'label' => 'Descripción', 'slug' => 'descripcion', 'type' => 'textarea', 'sort_order' => 2]);
        SectionField::create(['section_id' => $portfolioSection->id, 'name' => 'imagen', 'label' => 'Imagen Principal', 'slug' => 'imagen', 'type' => 'image', 'sort_order' => 3]);
        SectionField::create(['section_id' => $portfolioSection->id, 'name' => 'categoria', 'label' => 'Categoría', 'slug' => 'categoria', 'type' => 'select', 'options' => ['options' => ['Branding', 'Diseño Web', 'Fotografía', 'Video']], 'sort_order' => 4]);
        SectionField::create(['section_id' => $portfolioSection->id, 'name' => 'link', 'label' => 'Enlace del Proyecto', 'slug' => 'link', 'type' => 'url', 'sort_order' => 5]);

        // Crear proyectos de ejemplo
        $projects = [
            ['titulo' => 'Rediseño Brand TechCorp', 'descripcion' => 'Nueva identidad visual completa', 'categoria' => 'Branding'],
            ['titulo' => 'Website Restaurante Delicia', 'descripcion' => 'Sitio web con reservas online', 'categoria' => 'Diseño Web'],
        ];

        foreach ($projects as $project) {
            $entry = ContentEntry::create([
                'section_id' => $portfolioSection->id,
                'client_id' => $client->id,
                'title' => $project['titulo'],
                'slug' => \Illuminate\Support\Str::slug($project['titulo']),
                'status' => 'published',
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
            $entry->setFieldValue('titulo', $project['titulo']);
            $entry->setFieldValue('descripcion', $project['descripcion']);
            $entry->setFieldValue('categoria', $project['categoria']);
        }
    }
}
