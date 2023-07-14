<?php

namespace App\Entity;

use App\Repository\BlogPostsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlogPostsRepository::class)]
class BlogPosts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;
    #[ORM\Column(type: "text", length: 65535)]
    private ?string $body = null;
    #[ORM\Column(length: 255)]
    private ?string $imageFilename = null;

    #[ORM\OneToMany(mappedBy: 'postId', targetEntity: Comments::class)]
    private Collection $comments;

    #[ORM\ManyToMany(targetEntity: Users::class, mappedBy: 'likedPosts')]
    #[ORM\JoinTable('users_like_posts')]
    private Collection $likedByUsers;

    #[ORM\ManyToMany(targetEntity: Users::class, mappedBy: 'favoritePosts')]
    #[ORM\JoinTable('users_favorite_posts')]
    private Collection $favoritedByUsers;

    #[ORM\ManyToMany(targetEntity: PostCategories::class, mappedBy: 'blogPosts')]
    private Collection $postCategories;

    public function __construct()
    {
        $this->likedByUsers = new ArrayCollection();
        $this->favoritedByUsers = new ArrayCollection();
        $this->postCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    /**
     * @param string|null $imageFilename
     */
    public function setImageFilename(?string $imageFilename): void
    {
        $this->imageFilename = $imageFilename;
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
            $comment->setPostId($this);
        }

        return $this;
    }

    public function removeComments(Comments $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPostId() === $this) {
                $comment->setPostId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getLikedByUsers(): Collection
    {
        return $this->likedByUsers;
    }

    public function addLikedByUser(Users $likedByUser): static
    {
        if (!$this->likedByUsers->contains($likedByUser)) {
            $this->likedByUsers->add($likedByUser);
            $likedByUser->addLikedPost($this);
        }

        return $this;
    }

    public function removeLikedByUser(Users $likedByUser): static
    {
        if ($this->likedByUsers->removeElement($likedByUser)) {
            $likedByUser->removeLikedPost($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getFavoritedByUsers(): Collection
    {
        return $this->favoritedByUsers;
    }

    public function addFavoritedByUser(Users $favoritedByUser): static
    {
        if (!$this->favoritedByUsers->contains($favoritedByUser)) {
            $this->favoritedByUsers->add($favoritedByUser);
            $favoritedByUser->addFavoritePost($this);
        }

        return $this;
    }

    public function removeFavoritedByUser(Users $favoritedByUser): static
    {
        if ($this->favoritedByUsers->removeElement($favoritedByUser)) {
            $favoritedByUser->removeFavoritePost($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, PostCategories>
     */
    public function getPostCategories(): Collection
    {
        return $this->postCategories;
    }

    public function addPostCategory(PostCategories $postCategory): static
    {
        if (!$this->postCategories->contains($postCategory)) {
            $this->postCategories->add($postCategory);
            $postCategory->addBlogPost($this);
        }

        return $this;
    }

    public function removePostCategory(PostCategories $postCategory): static
    {
        if ($this->postCategories->removeElement($postCategory)) {
            $postCategory->removeBlogPost($this);
        }

        return $this;
    }

}
