<?php

namespace App\Entity;

use App\Repository\ParticipantsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ParticipantsRepository::class)
 * @UniqueEntity(fields={"pseudo"}, message="Il existe déjà un compte avec ce pseudo")
 */
class Participants implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "Votre pseudo ne doit pas avoir plus de {{ limit }} caractères"
     * )
     * @ORM\Column(type="string", length=30, unique=true)
     *
     */
    private $pseudo;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @Assert\Length(
     *      max = 20,
     *      maxMessage = "Votre mot de passe ne doit pas avoir plus de {{ limit }} caractères"
     * )
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "Votre nom ne doit pas avoir plus de {{ limit }} caractères"
     * )
     * @ORM\Column(type="string", length=30)
     */
    private $nom;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "Votre prénom ne doit pas avoir plus de {{ limit }} caractères"
     * )
     * @ORM\Column(type="string", length=30)
     */
    private $prenom;

    /**
     * @Assert\Regex(
     *     pattern="/^0[1-68]([-. ]?\d{2}){4}$/",
     *     message="Respectez le format numero mobile portable  "
     * )
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Votre numero mobile ne doit pas avoir plus de {{ limit }} chiffres"
     * )
     * @ORM\Column(type="string", length=15,nullable=true)
     */

    private $telephone;

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/",
     *     message="Format de l'email non valide"
     * )
     * @Assert\Length(
     *      max = 20,
     *      maxMessage = "Votre email mobile ne doit pas avoir plus de {{ limit }} caractères"
     * )
     * @ORM\Column(type="string", length=20)
     */
    private $mail;

    /**
     * @ORM\Column(type="boolean")
     */
    private $administrateur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur")
     */
    private $sorties;

    /**
     * fetch = permet le forcage d'acces au site du participant
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="participants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, inversedBy="participantsInscrit")
     */
    private $sortiesInscrit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $maPhoto;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
        $this->sortiesInscrit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties[] = $sorty;
            $sorty->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        if ($this->sorties->removeElement($sorty)) {
            // set the owning side to null (unless already changed)
            if ($sorty->getOrganisateur() === $this) {
                $sorty->setOrganisateur(null);
            }
        }

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortiesInscrit(): Collection
    {
        return $this->sortiesInscrit;
    }

    public function addSortiesInscrit(Sortie $sortiesInscrit): self
    {
        if (!$this->sortiesInscrit->contains($sortiesInscrit)) {
            $this->sortiesInscrit[] = $sortiesInscrit;
        }

        return $this;
    }

    public function removeSortiesInscrit(Sortie $sortiesInscrit): self
    {
        $this->sortiesInscrit->removeElement($sortiesInscrit);

        return $this;
    }

    public function getMaPhoto(): ?string
    {
        return $this->maPhoto;
    }

    public function setMaPhoto(?string $maPhoto): self
    {
        $this->maPhoto = $maPhoto;

        return $this;
    }
}
