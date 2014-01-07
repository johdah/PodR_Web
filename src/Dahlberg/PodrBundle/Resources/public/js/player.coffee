class Player
  audio = new Audio
  currentEpisode = -1
  timeSinceLastUpdate = 0;

  constructors: ->

  loadPlayer: ->
    $(".play-btn").on "click", ->
      id = $(this).attr('id').replace(/[^0-9]/g, '')
      console.log "Playing episode " + id
      loadEpisode id

    # Forward
    $("#player-forward").on "click", (e) ->
      e.preventDefault()
      forwardAudio()

    $("#player-fast-forward").on "click", (e) ->
      e.preventDefault()
      fastForwardAudio()

    # Play/Pause
    $("#player-pause").on "click", (e) ->
      e.preventDefault()
      pauseAudio()

    $("#player-play").on "click", (e) ->
      e.preventDefault()
      playAudio()

    # Rewind
    $("#player-fast-rewind").on "click", (e) ->
      e.preventDefault()
      fastRewindAudio()

    $("#player-rewind").on "click", (e) ->
      e.preventDefault()
      rewindAudio()

    # Stop
    $("#player-stop").on "click", (e) ->
      e.preventDefault()
      stopAudio()

  # Events
  timeUpdate = ->
    currentTime = parseInt(audio.currentTime, 10)
    maxTime = audio.duration
    currentPercentage = parseInt((currentTime/maxTime)*101)
    $("#player-seekbar .meter").css "width", currentPercentage + "%"

    @timeSinceLastTimeUpdate++
    if @timeSinceLastTimeUpdate > 60
      @timeSinceLastTimeUpdate = 0
      updateCurrentTimeOnServer()

  # API Functions
  loadEpisode = (id) ->
    currentEpisode = id
    loadedPosition = 0
    jqxhr = $.getJSON("/api/v1/episode/" + id, ->
    ).done((data)->
      audio.setAttribute "src", data["episode"]["enclosureUrl"]
      audio.setAttribute "type", data["episode"]["enclosureType"]
      $("#player-episode-title").text data["episode"]["title"]
      $("#player-podcast-title").text data["episode"]["podcast"]["title"]
      loadedPosition = parseInt(data["userEpisode"]["currentPosition"], 10)
    ).fail(->
      console.log "Player - Error occured while loading episode"
    ).always(->
      audio.addEventListener "canplay", (->
        console.log "Load position: " + loadedPosition
        audio.currentTime = loadedPosition
      ), false
      audio.load()

      # Event: TimeUpdate
      $(audio).bind "timeupdate", ->
        timeUpdate()

      playAudio()
    )

  updateCurrentTimeOnServer = ->
    # API Call: Archive episode
    jqxhr = $.post("/api/v1/userepisode/patch/" + currentEpisode,
      currentPosition: parseInt(audio.currentTime, 10)
      , ->
    ).done((data)->
    ).fail(->
      console.log "Player - Error occured while updating current time"
    ).always(->
    )

  # Actions
  fastRewindAudio = ->
    audio.currentTime -= 60.0

  fastForwardAudio = ->
    audio.currentTime += 60.0

  forwardAudio = ->
    audio.currentTime += 10.0

  rewindAudio = ->
    audio.currentTime -= 10.0

  pauseAudio = ->
    audio.pause()
    $("#player-pause").hide()
    $("#player-play").show()
    updateCurrentTimeOnServer()

  playAudio = ->
    audio.play()
    $("#player-pause").show()
    $("#player-play").hide()

  stopAudio = ->
    audio.pause()
    audio.currentTime = 0
    $("#player-pause").hide()
    $("#player-play").show()
    updateCurrentTimeOnServer()

$(document).ready ->
  player = new Player
  player.loadPlayer()