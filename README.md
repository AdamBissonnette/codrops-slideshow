# codrops_slideshow_wpplugin
A WordPress plugin to generate a slideshow from this: https://github.com/codrops/AnimatedFrameSlideshow

See original demo: https://tympanus.net/Development/AnimatedFrameSlideshow/

## Usage
A simple shortcode can be employed to load up the corresponding demo.

    [codrops_slideshow type="demo-1" height="60vh"][codrops_slide image="http://localhost:8888/media/25.jpg" title="Slide 1" description="Lorem Ipsum" link_text="See More" link_url="#" isfirst="true" /][codrops_slide image="http://localhost:8888/media/26.jpg" title="Slide 2" description="Lorem Ipsum" link_text="See More" link_url="#" isfirst="false" /][codrops_slide image="http://localhost:8888/media/27.jpg" title="Slide 3" description="Lorem Ipsum" link_text="See More" link_url="#" isfirst="false" /][/codrops_slideshow] 

## Todo

- Add support for "split" slideshow (demo-4 and demo-6)
- Stop renderblocking of entire page if images aren't found
- Create a shortcode builder