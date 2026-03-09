<?php

namespace App\Livewire;

use App\Models\WeddingTemplate as WeddingTemplateModel;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class WeddingTemplate extends Component
{
    use WithFileUploads;

    public ?WeddingTemplateModel $templateModel = null;

    // Content
    public $title = 'Wendy & Omar';
    public $subtitle = 'Nos Casamos';
    public $date = '20 Nov 2023';
    public $locationButtonText = 'Añadir al calendario';
    public $mapUrl = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3762.661608693245!2d-99.1686936850934!3d19.427020686887555!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d1ff35f5bd1563%3A0x6c366f0e2de02ff7!2sEl%20%C3%81ngel%20de%20la%20Independencia!5e0!3m2!1ses-419!2smx!4v1634589234567!5m2!1ses-419!2smx';
    
    // Images
    public $heroImage; // Holds the temporary upload object or URL string
    public $storyImage; // Holds the temporary upload object or URL string
    public $galleryImages = []; // Holds the temporary upload objects or URL strings
    
    // Story
    public $storyTitle = 'Nuestra Historia';
    public $storyText = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.';
    public $storyGroomName = 'Omar';
    public $storyGroomText = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
    public $storyBrideName = 'Wendy'; // Implicit in title, but let's add it
    public $storyBrideText = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

    // Quote
    public $quoteText = 'Andábamos sin buscarnos, pero sabiendo que andábamos para encontrarnos';
    public $quoteAuthor = '- Julio Cortázar';

    // Countdown
    public $eventDate = '2023-11-20T18:00';

    // Details
    public $ceremonyTitle = 'CEREMONIA';
    public $ceremonyTime = 'Hora de inicio: 18:00';
    public $ceremonyLocation = 'Hacienda San José';
    public $receptionTitle = 'RECEPCIÓN';
    public $receptionTime = 'Hora de inicio: 21:00';
    public $receptionLocation = 'Hacienda San José';

    // Style
    public $theme = 'Lino'; // Lino, Medianoche, Sabio, Bebé azul
    public $primaryColor = '#8B8B8B'; // Grayish from header
    public $secondaryColor = '#E5E0D8'; // Beige/Cream
    public $fontFamily = 'serif';

    public $isAdmin = false;
    public $showEditor = false;

    public function mount()
    {
        // Load existing template or create default
        $this->templateModel = WeddingTemplateModel::first();

        if ($this->templateModel) {
            $this->loadFromModel();
        } else {
            // Default Images
            $this->heroImage = 'https://images.unsplash.com/photo-1519741497674-611481863552?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80';
            $this->storyImage = 'https://images.unsplash.com/photo-1583939003579-730e3918a45a?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80';
            $this->galleryImages = [
                'https://images.unsplash.com/photo-1519741497674-611481863552?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80'
            ];
        }

        // Check if user is logged in and has admin role
        // For demo purposes, we can also check a query param or just Auth
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $this->isAdmin = $user && $user->hasRole('admin');
        
        // Open editor by default for admin
        if ($this->isAdmin) {
            $this->showEditor = true;
        }
    }

    public function loadFromModel()
    {
        $this->title = $this->templateModel->title ?? $this->title;
        $this->subtitle = $this->templateModel->subtitle ?? $this->subtitle;
        $this->date = $this->templateModel->date_text ?? $this->date;
        $this->locationButtonText = $this->templateModel->location_button_text ?? $this->locationButtonText;
        
        if ($this->templateModel->hero_image) {
            $this->heroImage = filter_var($this->templateModel->hero_image, FILTER_VALIDATE_URL) 
                ? $this->templateModel->hero_image 
                : Storage::url($this->templateModel->hero_image);
        }
        
        if ($this->templateModel->story_image) {
            $this->storyImage = filter_var($this->templateModel->story_image, FILTER_VALIDATE_URL) 
                ? $this->templateModel->story_image 
                : Storage::url($this->templateModel->story_image);
        }
        
        if ($this->templateModel->gallery_images) {
             $this->galleryImages = array_map(fn($path) => 
                filter_var($path, FILTER_VALIDATE_URL) ? $path : Storage::url($path), 
                $this->templateModel->gallery_images
             );
        }

        $this->storyTitle = $this->templateModel->story_title ?? $this->storyTitle;
        $this->storyText = $this->templateModel->story_text ?? $this->storyText;
        $this->storyGroomName = $this->templateModel->story_groom_name ?? $this->storyGroomName;
        $this->storyGroomText = $this->templateModel->story_groom_text ?? $this->storyGroomText;
        $this->storyBrideName = $this->templateModel->story_bride_name ?? $this->storyBrideName;
        $this->storyBrideText = $this->templateModel->story_bride_text ?? $this->storyBrideText;

        $this->quoteText = $this->templateModel->quote_text ?? $this->quoteText;
        $this->quoteAuthor = $this->templateModel->quote_author ?? $this->quoteAuthor;

        $this->eventDate = $this->templateModel->event_date ? $this->templateModel->event_date->format('Y-m-d\TH:i') : $this->eventDate;

        $this->ceremonyTitle = $this->templateModel->ceremony_title ?? $this->ceremonyTitle;
        $this->ceremonyTime = $this->templateModel->ceremony_time ?? $this->ceremonyTime;
        $this->ceremonyLocation = $this->templateModel->ceremony_location ?? $this->ceremonyLocation;

        $this->receptionTitle = $this->templateModel->reception_title ?? $this->receptionTitle;
        $this->receptionTime = $this->templateModel->reception_time ?? $this->receptionTime;
        $this->receptionLocation = $this->templateModel->reception_location ?? $this->receptionLocation;

        $this->theme = $this->templateModel->theme ?? $this->theme;
        $this->primaryColor = $this->templateModel->primary_color ?? $this->primaryColor;
        $this->secondaryColor = $this->templateModel->secondary_color ?? $this->secondaryColor;
        $this->mapUrl = $this->templateModel->map_url ?? $this->mapUrl;
    }

    public function save()
    {
        if (!$this->isAdmin) return;

        // Clean Map URL if it contains iframe tag
        if (str_contains($this->mapUrl, '<iframe')) {
            preg_match('/src="([^"]+)"/', $this->mapUrl, $matches);
            if (isset($matches[1])) {
                $this->mapUrl = $matches[1];
            }
        }

        $data = [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'date_text' => $this->date,
            'location_button_text' => $this->locationButtonText,
            'story_title' => $this->storyTitle,
            'story_text' => $this->storyText,
            'story_groom_name' => $this->storyGroomName,
            'story_groom_text' => $this->storyGroomText,
            'story_bride_name' => $this->storyBrideName,
            'story_bride_text' => $this->storyBrideText,
            'quote_text' => $this->quoteText,
            'quote_author' => $this->quoteAuthor,
            'event_date' => $this->eventDate,
            'ceremony_title' => $this->ceremonyTitle,
            'ceremony_time' => $this->ceremonyTime,
            'ceremony_location' => $this->ceremonyLocation,
            'reception_title' => $this->receptionTitle,
            'reception_time' => $this->receptionTime,
            'reception_location' => $this->receptionLocation,
            'theme' => $this->theme,
            'primary_color' => $this->primaryColor,
            'secondary_color' => $this->secondaryColor,
            'map_url' => $this->mapUrl,
        ];

        // Handle File Uploads
        if ($this->heroImage && !is_string($this->heroImage)) {
             $data['hero_image'] = $this->heroImage->store('wedding-images', 'public');
        }

        if ($this->storyImage && !is_string($this->storyImage)) {
             $data['story_image'] = $this->storyImage->store('wedding-images', 'public');
        }

        // Handle Gallery Uploads
        $finalGallery = [];
        if (!empty($this->galleryImages)) {
            foreach ($this->galleryImages as $img) {
                if ($img instanceof \Illuminate\Http\UploadedFile) {
                    $finalGallery[] = $img->store('wedding-images', 'public');
                } elseif (is_string($img)) {
                    // Check if it's a local storage URL and extract path
                    if (str_contains($img, '/storage/')) {
                        $parts = explode('/storage/', $img);
                        if (isset($parts[1])) {
                            $finalGallery[] = $parts[1];
                        } else {
                             $finalGallery[] = $img;
                        }
                    } else {
                        $finalGallery[] = $img;
                    }
                }
            }
        }
        
        if (!empty($finalGallery)) {
            $data['gallery_images'] = $finalGallery;
        }

        if ($this->templateModel) {
            $this->templateModel->update($data);
        } else {
            $this->templateModel = WeddingTemplateModel::create($data);
        }

        // Reload to get correct URLs
        $this->loadFromModel();
        
        session()->flash('message', 'Cambios guardados exitosamente.');
    }

    public function updatedTheme($value)
    {
        switch ($value) {
            case 'Lino':
                $this->primaryColor = '#8B8B8B';
                $this->secondaryColor = '#E5E0D8';
                break;
            case 'Medianoche':
                $this->primaryColor = '#1a202c';
                $this->secondaryColor = '#2d3748';
                break;
            case 'Sabio':
                $this->primaryColor = '#4a5568';
                $this->secondaryColor = '#cbd5e0';
                break;
            case 'Bebé azul':
                $this->primaryColor = '#4299e1';
                $this->secondaryColor = '#bee3f8';
                break;
        }
    }

    public function render()
    {
        return view('livewire.wedding-template')->layout('components.layouts.wedding', [
            'title' => $this->title,
        ]);
    }
}
