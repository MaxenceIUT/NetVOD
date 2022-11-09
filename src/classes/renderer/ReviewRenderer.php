<?php

namespace iutnc\netvod\renderer;

use iutnc\netvod\data\Review;

class ReviewRenderer implements Renderer
{

    private Review $review;

    /**
     * @param mixed $review
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    function render(int $mode): string
    {
        if ($mode == Renderer::FULL) {
            return <<<END
            <div class="review">
                <div class="user">
                    <p>{$this->review->email}</p>                
                </div>
                <div class="comment">
                    <h4>{$this->review->score}</h4>
                    <p>{$this->review->comment}</p>
                </div>
            </div>
            END;
        } else {
            return "";
        }
    }

}