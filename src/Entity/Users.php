<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method string getUserIdentifier()
 */
#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: Comments::class)]
    private Collection $comments;


    #[ORM\Column(type:"json")]
    private $roles = [];

    #[ORM\ManyToMany(targetEntity: BlogPosts::class, inversedBy: 'likedByUsers')]
    private Collection $likedPosts;

    #[ORM\ManyToMany(targetEntity: BlogPosts::class, inversedBy: 'favoritedByUsers')]
    private Collection $favoritePosts;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->likedPosts = new ArrayCollection();
        $this->favoritePosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setUserId($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUserId() === $this) {
                $comment->setUserId(null);
            }
        }

        return $this;
    }


    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
        return $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return Collection<int, BlogPosts>
     */
    public function getLikedPosts(): Collection
    {
        return $this->likedPosts;
    }

    public function addLikedPost(BlogPosts $likedPost): static
    {
        if (!$this->likedPosts->contains($likedPost)) {
            $this->likedPosts->add($likedPost);
        }

        return $this;
    }

    public function removeLikedPost(BlogPosts $likedPost): static
    {
        $this->likedPosts->removeElement($likedPost);

        return $this;
    }

    /**
     * @return Collection<int, BlogPosts>
     */
    public function getFavoritePosts(): Collection
    {
        return $this->favoritePosts;
    }

    public function addFavoritePost(BlogPosts $favoritePost): static
    {
        if (!$this->favoritePosts->contains($favoritePost)) {
            $this->favoritePosts->add($favoritePost);
        }

        return $this;
    }

    public function removeFavoritePost(BlogPosts $favoritePost): static
    {
        $this->favoritePosts->removeElement($favoritePost);

        return $this;
    }
}
